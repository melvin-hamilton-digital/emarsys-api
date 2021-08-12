<?php

namespace Tests\Unit;

use DateTime;
use MHD\Emarsys\Data\ContactFields;
use PHPUnit\Framework\TestCase;

class ContactFieldsTest extends TestCase
{
    /**
     * @var ContactFields
     */
    private $contactFields;

    public function setUp(): void
    {
        $this->contactFields = new ContactFields();
    }

    /**
     * @dataProvider setFieldValueByIdDataProvider
     */
    public function testSetFieldValueById(int $id, $value)
    {
        $this->contactFields->setFieldValueById($id, $value);

        $fields = $this->contactFields->getFields();
        $this->assertArrayHasKey($id, $fields);
        $this->assertEquals($value, $fields[$id]);
    }

    public function setFieldValueByIdDataProvider()
    {
        yield [1, 1];
        yield [2, 'foobar'];
        yield [3, null];
        yield [4, true];
    }

    /**
     * @dataProvider setDateOfBirthDataProvider
     */
    public function testSetDateOfBirth(DateTime $dateOfBirth, string $expected)
    {
        $this->contactFields->setDateOfBirth($dateOfBirth);

        $fields = $this->contactFields->getFields();
        $this->assertArrayHasKey(ContactFields::ID_DATE_OF_BIRTH, $fields);
        $this->assertEquals($expected, $fields[ContactFields::ID_DATE_OF_BIRTH]);
    }

    public function setDateOfBirthDataProvider()
    {
        yield [
            new DateTime('@0'),
            '1970-01-01'
        ];

        yield [
            new DateTime('@1234567890'),
            '2009-02-13'
        ];
    }

    /**
     * @dataProvider setOptInDataProvider
     */
    public function testSetOptIn(bool $optIn, int $expected)
    {
        $this->contactFields->setOptIn($optIn);

        $fields = $this->contactFields->getFields();
        $this->assertArrayHasKey(ContactFields::ID_OPT_IN, $fields);
        $this->assertEquals($expected, $fields[ContactFields::ID_OPT_IN]);
    }

    public function setOptInDataProvider()
    {
        yield [true, 1];
        yield [false, 2];
    }
}
