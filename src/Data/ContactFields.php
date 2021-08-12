<?php

namespace MHD\Emarsys\Data;

use DateTime;

class ContactFields
{
    /**
     * @link https://dev.emarsys.com/v2/personalization/contact-system-fields
     */
    public const UID = 'uid';

    public const ID_FIRST_NAME = 1;
    public const ID_LAST_NAME = 2;
    public const ID_EMAIL = 3;
    public const ID_DATE_OF_BIRTH = 4;
    public const ID_GENDER = 5;
    public const ID_TITLE = 9;
    public const ID_OPT_IN = 31;
    public const ID_REGISTRATION_LANGUAGE = 35;

    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;
    public const GENDER_PREFER_NOT_TO_SAY = 3;

    public const DATE_FORMAT = 'Y-m-d';

    public const LANGUAGE_ENGLISH = 1;
    public const LANGUAGE_GERMAN = 2;
    public const LANGUAGE_FRENCH = 3;

    private $fields = [];

    public function setFieldValueById(int $id, $value)
    {
        $this->fields[$id] = $value;
    }

    public function setFirstName(string $firstName)
    {
        $this->setFieldValueById(self::ID_FIRST_NAME, $firstName);
    }

    public function setLastName(string $lastName)
    {
        $this->setFieldValueById(self::ID_LAST_NAME, $lastName);
    }

    public function setEmail(string $email)
    {
        $this->setFieldValueById(self::ID_EMAIL, $email);
    }

    public function setDateOfBirth(DateTime $dateOfBirth)
    {
        $this->setFieldValueById(self::ID_DATE_OF_BIRTH, $dateOfBirth->format(self::DATE_FORMAT));
    }

    public function setGender(int $gender)
    {
        $this->setFieldValueById(self::ID_GENDER, $gender);
    }

    public function setOptIn(bool $optIn)
    {
        $this->setFieldValueById(self::ID_OPT_IN, $optIn ? 1 : 2);  // see documentation
    }

    public function setRegistrationLanguage(int $language)
    {
        $this->setFieldValueById(self::ID_REGISTRATION_LANGUAGE, $language);
    }

    public function setUid(string $uid)
    {
        $this->fields[self::UID] = $uid;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
