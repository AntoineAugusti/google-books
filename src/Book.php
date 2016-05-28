<?php

namespace AntoineAugusti\Books;

use DateTime;

class Book
{
    public $title;
    public $subtitle;
    public $authors;
    public $printType;
    public $pageCount;
    public $publisher;
    public $publishedDate;
    public $averageRating;
    public $thumbnail;
    public $language;
    public $categories;

    /**
     * New up a book.
     *
     * @param string   $title
     * @param string   $subtitle
     * @param array    $authors
     * @param string   $printType
     * @param int      $pageCount
     * @param string   $publisher
     * @param DateTime $publishedDate
     * @param int      $averageRating
     * @param string   $thumbnail
     * @param string   $language
     * @param array    $categories
     */
    public function __construct($title, $subtitle, array $authors, $printType, $pageCount, $publisher, DateTime $publishedDate, $averageRating, $thumbnail, $language, array $categories)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->authors = $authors;
        $this->printType = $printType;
        $this->pageCount = $pageCount;
        $this->publisher = $publisher;
        $this->publishedDate = $publishedDate;
        $this->averageRating = $averageRating;
        $this->thumbnail = $thumbnail;
        $this->language = $language;
        $this->categories = $categories;
    }
}
