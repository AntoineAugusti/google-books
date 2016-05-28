<?php

require 'vendor/autoload.php';

use AntoineAugusti\Books\Fetcher;
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'https://www.googleapis.com/books/v1/']);
$fetcher = new Fetcher($client);
$book = $fetcher->forISBN('9780142181119');

var_dump($book);
// class AntoineAugusti\Books\Book#32 (11) {
//   public $title => string(15) "Average Is Over"
//   public $subtitle => string(55) "Powering America Beyond the Age of the Great Stagnation"
//   public $authors =>
//   array(1) {
//     [0] => string(11) "Tyler Cowen"
//   }
//   public $printType => string(4) "BOOK"
//   public $pageCount => int(290)
//   public $publisher => string(5) "Plume"
//   public $publishedDate =>
//   class DateTime#33 (3) {
//     public $date => string(26) "2014-08-26 00:00:00.000000"
//     public $timezone_type => int(3)
//     public $timezone => string(13) "Europe/London"
//   }
//   public $averageRating => double(3)
//   public $thumbnail => string(100) "http://books.google.fr/bookscontent?id=-Zp_ngEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api"
//   public $language => string(2) "en"
//   public $categories =>
//   array(1) {
//     [0] => string(20) "Business & Economics"
//   }
// }
