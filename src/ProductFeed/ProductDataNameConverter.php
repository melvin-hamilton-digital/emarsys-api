<?php

namespace MHD\Emarsys\ProductFeed;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use Symfony\Component\Serializer\NameConverter\AdvancedNameConverterInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class ProductDataNameConverter implements AdvancedNameConverterInterface
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
    private $customFieldsCache = [];

    public function __construct(
        NameConverterInterface $nameConverter = null,
        Reader                 $reader = null
    ) {
        $this->nameConverter = $nameConverter ?? new CamelCaseToSnakeCaseNameConverter();
        $this->reader = $reader ?? new AnnotationReader();
    }

    public function normalize(string $propertyName, string $class = null, string $format = null, array $context = [])
    {
        $normalizedPropertyName = $this->nameConverter->normalize($propertyName);

        if ($class === null) {
            return $normalizedPropertyName;
        }

        $customFields = $this->getCustomFields($class);
        if (in_array($propertyName, $customFields)) {
            $normalizedPropertyName = "c_{$normalizedPropertyName}";
        }

        return $normalizedPropertyName;
    }

    public function denormalize(string $propertyName, string $class = null, string $format = null, array $context = [])
    {
        if (str_starts_with($propertyName, 'c_')) {
            $propertyName = substr($propertyName, 2);
        }

        return $this->nameConverter->denormalize($propertyName);
    }

    public function getCustomFields(string $class): array
    {
        if (!isset($this->customFieldsCache[$class])) {
            $this->customFieldsCache[$class] = $this->getPropertiesWithCustomFieldAnnotation($class);
        }

        return $this->customFieldsCache[$class];
    }

    public function getPropertiesWithCustomFieldAnnotation(string $class): array
    {
        $properties = [];
        $reflectionClass = new ReflectionClass($class);
        foreach ($reflectionClass->getProperties() as $property) {
            if ($this->reader->getPropertyAnnotation($property, CustomField::class)) {
                $properties[] = $property->getName();
            }
        }

        return $properties;
    }
}
