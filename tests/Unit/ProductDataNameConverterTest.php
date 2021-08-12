<?php

namespace Tests\Unit;

use MHD\Emarsys\ProductFeed\ProductDataNameConverter;
use PHPUnit\Framework\TestCase;

class ProductDataNameConverterTest extends TestCase
{
    /**
     * @var ProductDataNameConverter
     */
    private $nameConverter;

    public function setUp(): void
    {
        $this->nameConverter = new ProductDataNameConverter();
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize(string $propertyName, string $class, string $expected)
    {
        $normalizedPropertyName = $this->nameConverter->normalize($propertyName, $class);

        $this->assertEquals($expected, $normalizedPropertyName);
    }

    public function normalizeDataProvider()
    {
        $testClass = get_class(
            new class () {
                public $foo;
                /**
                 * @MHD\Emarsys\ProductFeed\CustomField
                 */
                public $bar;
            }
        );

        yield [
            'foo',
            $testClass,
            'foo'
        ];

        yield [
            'bar',
            $testClass,
            'c_bar'
        ];
    }

    /**
     * @dataProvider denormalizeDataProvider
     */
    public function testDenormalize(string $propertyName, string $expected)
    {
        $denormalizedPropertyName = $this->nameConverter->denormalize($propertyName);

        $this->assertEquals($expected, $denormalizedPropertyName);
    }

    public function denormalizeDataProvider()
    {
        yield ['foo', 'foo'];
        yield ['c_foo', 'foo'];
        yield ['foo_bar', 'fooBar'];
        yield ['c_foo_bar', 'fooBar'];
    }

    public function testGetPropertiesWithCustomFieldAnnotation()
    {
        $testClass = get_class(
            new class() {
                public $foo;
                /**
                 * @MHD\Emarsys\ProductFeed\CustomField
                 */
                public $bar;
            }
        );

        $this->assertEquals(
            ['bar'],
            $this->nameConverter->getPropertiesWithCustomFieldAnnotation($testClass)
        );
    }
}
