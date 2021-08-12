<?php

namespace MHD\Emarsys\Data;

use DateTime;

/**
 * @link https://help.emarsys.com/hc/en-us/articles/360003070654-Preparing-your-sales-data-file
 */
class SalesData
{
    /**
     * @var string
     */
    public $item;
    /**
     * @var float
     */
    public $price;
    /**
     * @var string
     */
    public $order;
    /**
     * @var DateTime
     */
    public $timestamp;
    /**
     * @var string
     */
    public $customer;
    /**
     * @var string
     */
    public $email;
    /**
     * @var float
     */
    public $quantity;
}
