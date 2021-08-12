<?php

namespace MHD\Emarsys\SalesFeed;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class CustomField
{
    /**
     * @Enum({"int", "integer", "float", "timestamp", "string"})
     */
    public $type;
}
