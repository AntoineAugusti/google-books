<a href="https://styleci.io/repos/59841617"><img src="https://styleci.io/repos/59841617/shield" alt="StyleCI Status"></img></a>
<a href="https://travis-ci.org/AntoineAugusti/google-books"><img src="https://img.shields.io/travis/AntoineAugusti/google-books/master.svg?style=flat-square" alt="Build Status"></img></a>
[![Coverage Status](http://codecov.io/github/AntoineAugusti/google-books/coverage.svg?branch=master)](http://codecov.io/github/AntoineAugusti/google-books?branch=master)
<a href="https://scrutinizer-ci.com/g/AntoineAugusti/google-books"><img src="https://img.shields.io/scrutinizer/g/AntoineAugusti/google-books.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></img></a>
<a href="https://github.com/AntoineAugusti/google-books/releases"><img src="https://img.shields.io/github/release/AntoineAugusti/google-books.svg?style=flat-square" alt="Latest Version"></img></a>

Google Books
============
A simple client for the Google Books API, with limited functionality for now.

## Installation

Either [PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+ are required.

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require antoineaugusti/google-books
```

Instead, you may of course manually update your require block and run `composer update` if you want.



## Usage

Getting information for a book thanks to its ISBN 10 or ISBN 13 number:
```php
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
```


## License

This package is licensed under [The MIT License (MIT)](LICENSE.md).
