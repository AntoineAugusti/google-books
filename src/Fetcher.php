<?php

namespace AntoineAugusti\Books;

use DateTime;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;

class Fetcher
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve information about a book given its ISBN.
     *
     * @param string $isbn
     *
     * @throws InvalidArgumentException When the ISBN has not the expected format
     * @throws InvalidResponseException When the client got an unexpected response
     *
     * @return Book
     */
    public function forISBN($isbn)
    {
        if (!$this->isValidISBN($isbn)) {
            throw new InvalidArgumentException('ISBN is not valid. Got: '.$isbn);
        }

        // Example: https://www.googleapis.com/books/v1/volumes?q=isbn:9780142181119
        $response = $this->client->request('GET', 'volumes', [
            'query'       => ['q' => 'isbn:'.$isbn],
            'http_errors' => false,
        ]);

        $status = $response->getStatusCode();
        if ($status != 200) {
            throw new InvalidResponseException('Invalid response. Status: '.$status.'. Body: '.$response->getBody());
        }

        $res = json_decode($response->getBody(), true);

        $totalItems = intval($res['totalItems']);
        if ($totalItems != 1) {
            throw new InvalidResponseException('Did not get 1 result. Got: '.$totalItems);
        }

        return $this->extractBook($res);
    }

    private function extractBook($res)
    {
        $item = $res['items'][0];

        $publishedDate = $this->getOrDefault($item['volumeInfo'], 'publishedDate', null);
        if (!is_null($publishedDate)) {
            $publishedDate = DateTime::createFromFormat('Y-m-d', $item['volumeInfo']['publishedDate'])->setTime(0, 0);
        }

        return new Book($item['volumeInfo']['title'],
            $this->getOrDefault($item['volumeInfo'], 'subtitle', null),
            $this->getOrDefault($item['volumeInfo'], 'authors', null),
            $this->getOrDefault($item['volumeInfo'], 'printType', null),
            intval($this->getOrDefault($item['volumeInfo'], 'pageCount', null)),
            $this->getOrDefault($item['volumeInfo'], 'publisher', null),
            $publishedDate,
            $this->getOrDefault($item['volumeInfo'], 'averageRating', null),
            $item['volumeInfo']['imageLinks']['thumbnail'],
            $this->getOrDefault($item['volumeInfo'], 'language', null),
            $this->getOrDefault($item['volumeInfo'], 'categories', []));
    }

    private function getOrDefault($array, $key, $default)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Check if a given ISBN is valid.
     *
     * @param string $isbn
     *
     * @return bool
     */
    private function isValidISBN($isbn)
    {
        return preg_match('/[0-9]{10,13}/', $isbn);
    }
}
