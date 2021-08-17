<?php

namespace MHD\Emarsys\SalesFeed;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 *
 * @link https://help.emarsys.com/hc/en-us/articles/360003070654-Preparing-your-sales-data-file#custom-fields
 */
class CustomField
{
    /**
     * @Enum({"int", "integer", "float", "timestamp", "string"})
     */
    public $type;
}
