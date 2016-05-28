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

        $item = $res['items'][0];

        return new Book($item['volumeInfo']['title'],
            $item['volumeInfo']['subtitle'],
            $item['volumeInfo']['authors'],
            $item['volumeInfo']['printType'],
            intval($item['volumeInfo']['pageCount']),
            $item['volumeInfo']['publisher'],
            DateTime::createFromFormat('Y-m-d', $item['volumeInfo']['publishedDate'])->setTime(0, 0),
            $item['volumeInfo']['averageRating'],
            $item['volumeInfo']['imageLinks']['thumbnail'],
            $item['volumeInfo']['language'],
            $item['volumeInfo']['categories']);
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
