<?php

namespace Tests\Unit;

use MHD\Emarsys\SalesFeed\SalesDataNameConverter;
use PHPUnit\Framework\TestCase;

class SalesDataNameConverterTest extends TestCase
{
    /**
     * @var SalesDataNameConverter
     */
    private $nameConverter;

    public function setUp(): void
    {
        $this->nameConverter = new SalesDataNameConverter();
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize(string $propertyName, string $class, string $expected)
    {
        $normalizedProperty = $this->nameConverter->normalize($propertyName, $class);
        $this->assertEquals($expected, $normalizedProperty);
    }

    public function normalizeDataProvider()
    {
        $testClass = get_class(
            new class() {
                public $foo;
                /**
                 * @MHD\Emarsys\SalesFeed\CustomField("int")
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
            'i_bar'
        ];
    }

    /**
     * @dataProvider denormalizeDataProvider
     */
    public function testDenormalize(string $propertyName, string $expected)
    {
        $denormalizedProperty = $this->nameConverter->denormalize($propertyName);
        $this->assertEquals($expected, $denormalizedProperty);
    }

    public function denormalizeDataProvider()
    {
        yield ['foo', 'foo'];

        yield ['i_foo', 'foo'];

        yield ['foo_bar', 'fooBar'];

        yield ['s_foo_bar', 'fooBar'];

        yield ['is_foo', 'isFoo'];
    }

    public function testGetPropertyNameToCustomFieldType()
    {
        $testClass = get_class(
            new class() {
                public $foo;
                /**
                 * @MHD\Emarsys\SalesFeed\CustomField("integer")
                 */
                public $bar;
                /**
                 * @MHD\Emarsys\SalesFeed\CustomField("string")
                 */
                public $baz;
            }
        );

        $this->assertEquals(
            ['bar' => 'integer', 'baz' => 'string'],
            $this->nameConverter->getPropertyNameToCustomFieldTypeMap($testClass)
        );
    }
}
