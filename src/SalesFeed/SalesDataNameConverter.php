<?php

namespace MHD\Emarsys\SalesFeed;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use Symfony\Component\Serializer\NameConverter\AdvancedNameConverterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class SalesDataNameConverter implements AdvancedNameConverterInterface
{
    /**
     * @var NameConverterInterface
     */
    private $nameConverter;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var array
     */
    private $customFieldTypesCache = [];

    public function __construct(
        NameConverterInterface $nameConverter = null,
        Reader                 $reader = null
    ) {
        $this->nameConverter = $nameConverter ?? new CamelCaseToSnakeCaseNameConverter();
        $this->reader = $reader ?? new AnnotationReader();
    }

    public function normalize(string $propertyName, string $class = null, string $format = null, array $context = [])
    {
        $normalizedProperty = $this->nameConverter->normalize($propertyName);

        if ($class === null) {
            return $normalizedProperty;
        }

        $customFieldTypes = $this->getCustomFieldTypes($class);
        if (array_key_exists($propertyName, $customFieldTypes)) {
            $prefix = substr($customFieldTypes[$propertyName], 0, 1);
            $normalizedProperty = "{$prefix}_{$normalizedProperty}";
        }

        return $normalizedProperty;
    }

    public function denormalize(string $propertyName, string $class = null, string $format = null, array $context = [])
    {
        if (preg_match('/^[ifts]_/', $propertyName)) {
            $propertyName = substr($propertyName, 2);
        }

        return $this->nameConverter->denormalize($propertyName);
    }

    public function getCustomFieldTypes(string $class): array
    {
        if (!isset($this->customFieldTypesCache[$class])) {
            $this->customFieldTypesCache[$class] = $this->getPropertyNameToCustomFieldTypeMap($class);
        }

        return $this->customFieldTypesCache[$class];
    }

    public function getPropertyNameToCustomFieldTypeMap(string $class): array
    {
        $map = [];
        $reflectionClass = new ReflectionClass($class);
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $annotation = $this->reader->getPropertyAnnotation($reflectionProperty, CustomField::class);
            if ($annotation) {
                $map[$reflectionProperty->getName()] = $annotation->type;
            }
        }

        return $map;
    }
}
