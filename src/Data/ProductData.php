<?php

namespace MHD\Emarsys\Data;

/**
 * @link https://help.emarsys.com/hc/en-us/articles/214245045-Preparing-your-product-data-file
 */
class ProductData
{
    /**
     * @var string
     */
    public $item;
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $link;
    /**
     * @var string
     */
    public $image;
    /**
     * @var string
     */
    public $zoomImage;
    /**
     * @var string
     */
    public $category;
    /**
     * @var string
     */
    public $available;
    /**
     * @var string
     */
    public $description;
    /**
     * @var float
     */
    public $price;
    /**
     * @var float
     */
    public $msrp;
    /**
     * @var string
     */
    public $brand;
}
