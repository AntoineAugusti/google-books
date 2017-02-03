<?php

namespace AntoineAugusti\Tests\Books;

use AntoineAugusti\Books\Fetcher;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class FetcherTest extends PHPUnit_Framework_TestCase
{
    /** @var \AntoineAugusti\Books\Fetcher */
    private $fetcher;

    protected function setUp()
    {
        $client = new Client(['base_uri' => 'https://www.googleapis.com/books/v1/']);
        $this->fetcher = new Fetcher($client);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage ISBN is not valid. Got: foo
     */
    public function it_tests_for_the_validity_of_the_iban()
    {
        $this->fetcher->forISBN('foo');
    }

    /**
     * @test
     */
    public function it_fetches_a_book()
    {
        // Fetch https://www.googleapis.com/books/v1/volumes?q=isbn:9780525953739
        $res = $this->fetcher->forISBN('9780525953739');

        $this->assertEquals('Average is Over', $res->title);
        $this->assertEquals('Powering America Beyond the Age of the Great Stagnation', $res->subtitle);
        $this->assertEquals(['Tyler Cowen'], $res->authors);
        $this->assertEquals('BOOK', $res->printType);
        $this->assertEquals(290, $res->pageCount);
        $this->assertEquals('Dutton Adult', $res->publisher);
        $this->assertEquals(new DateTime('2013-01-01'), $res->publishedDate);
        $this->assertEquals('Y', $res->publishedDateFormat);
        $this->assertGreaterThan(1, $res->averageRating);
        $this->assertTrue($this->startsWith($res->thumbnail, 'http://'));
        $this->assertEquals('en', $res->language);
        $this->assertEquals(['Business & Economics'], $res->categories);
    }

    /**
     * @test
     */
    public function it_sets_to_null_if_data_unavailable()
    {
        $res = $this->fetcher->forISBN('9780307957023');

        $this->assertNull($res->subtitle);
    }

    /**
     * @test
     */
    public function it_sets_to_empty_if_data_unavailable()
    {
        $res = $this->fetcher->forISBN('9781491929124');

        $this->assertEquals([], $res->categories);
    }

    /**
     * @test
     * @expectedException \AntoineAugusti\Books\InvalidResponseException
     * @expectedExceptionMessage Invalid response. Status: 404. Body:
     */
    public function it_handles_a_404()
    {
        $responses = [new Response(404)];

        (new Fetcher($this->clientWithMockResponses($responses)))->forISBN('1234567891');
    }

    /**
     * @test
     * @expectedException \AntoineAugusti\Books\InvalidResponseException
     * @expectedExceptionMessage Did not get 1 result. Got: 2
     */
    public function it_handles_multiple_results()
    {
        $responses = [new Response(200, ['Content-Type' => 'application/json'], '{"totalItems":2}')];

        (new Fetcher($this->clientWithMockResponses($responses)))->forISBN('1234567891');
    }

    private function clientWithMockResponses(array $responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);

        return new Client(compact('handler'));
    }

    private function startsWith($haystack, $needle)
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
