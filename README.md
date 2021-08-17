# Emarsys API

[![build](https://img.shields.io/github/workflow/status/melvin-hamilton-digital/emarsys-api/PHP%20Composer)](https://github.com/melvin-hamilton-digital/emarsys-api/actions)

## API documentation

https://dev.emarsys.com/v2/emarsys-developer-hub

## Current features

* contact management,
* triggering external events,
* fields management,
* product and sales feed generation,
* sales feed upload.

## Example usage

```php
use GuzzleHttp\Client as HttpClient;
use MHD\Emarsys\Api\Authentication;
use MHD\Emarsys\Api\Client as EmarsysClient;
use MHD\Emarsys\Api\Contacts;
use MHD\Emarsys\Api\Events;
use MHD\Emarsys\Data\ContactFields;

$authentication = new Authentication('username', 'secret');
$httpClient = new HttpClient(['base_uri' => EmarsysClient::BASE_URI]);
$emarsysClient = new EmarsysClient($authentication, $httpClient);

# create new contact
$newContact = (new ContactFields())
    ->setEmail('john.doe@example.org')
    ->setFirstName('John')
    ->setLastName('Doe')
    ->setGender(ContactFields::GENDER_MALE);

$contacts = new Contacts($emarsysClient);
$contacts->createContact($newContact);

# send email to John
$events = new Events($emarsysClient);
$events->triggerEvent(1234, 'john.doe@example.org');
```

## Product feed

Emarsys product feed can be either Google Product Feed or custom CSV format. While using Google Product Feed might be a
better idea, since it is supported not only by Emarsys, this library still provides:

* [`MHD\Emarsys\Data\ProductData`](./src/Data/ProductData.php) class, which covers all standard product data fields,
* [`MHD\Emarsys\ProductFeed\CustomField`](./src/ProductFeed/CustomField.php) annotation class
  and [`MHD\Emarsys\ProductFeed\ProductDataNameConverter`](./src/ProductFeed/ProductDataNameConverter.php)for custom
  product data fields,

Those classes are meant to be used with `symfony/serializer` library.

```php
use MHD\Emarsys\Data\ProductData;
use MHD\Emarsys\ProductFeed\ProductDataNameConverter;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class CustomProductData extends ProductData
{
    /**
     * @MHD\Emarsys\ProductFeed\CustomField
     */
    public $customField;
}

$productData = new CustomProductData;
$productData->item = 'foo';
$productData->customField = 'bar';

$normalizers = [
    new PropertyNormalizer(null, new ProductDataNameConverter()),
    new DateTimeNormalizer(),
];
$serializer = new Serializer($normalizers, [new CsvEncoder()]);

echo $serializer->serialize([$productData], 'csv');

# c_custom_field,item,title,link,image,zoom_image,category,available,description,price,msrp,brand
# bar,foo,,,,,,,,,,
```

## Sales feed

For Emarsys sales feed generation this library provides:

* [`MHD\Emarsys\Data\SalesData`](./src/Data/SalesData.php) class, which covers standard sales data field set,
* [`MHD\Emarsys\SalesFeed\CustomField`](./src/SalesFeed/CustomField.php) annotation class,
  and [`MHD\Emarsys\SalesFeed\SalesDataNameConverter`](./src/SalesFeed/SalesDataNameConverter.php) for sales data custom
  fields,
* [`MHD\Emarsys\SalesFeed\Uploader`](./src/SalesFeed/Uploader.php) class for automated sales feed upload.

```php
use MHD\Emarsys\Data\SalesData;
use MHD\Emarsys\SalesFeed\SalesDataNameConverter;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

class CustomSalesData extends SalesData
{
    /**
     * @MHD\Emarsys\SalesFeed\CustomField("string")
     */
    public $customField;
}

$salesData = new CustomSalesData;
$salesData->item = 'foo';
$salesData->customField = 'bar';

$normalizers = [
    new PropertyNormalizer(null, new SalesDataNameConverter()),
    new DateTimeNormalizer(),
];
$serializer = new Serializer($normalizers, [new CsvEncoder()]);

echo $serializer->serialize([$salesData], 'csv');

# s_custom_field,item,price,order,timestamp,customer,email,quantity
# bar,foo,,,,,,

```
