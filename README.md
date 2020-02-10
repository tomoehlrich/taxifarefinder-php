# TaxiFareFinder

This package can retrieve supported cities, taxi businesses and taxi fare information from the [TaxiFareFinder API](https://www.taxifarefinder.com/api.php).

The TaxiFareFinder API key can be requested under [https://www.taxifarefinder.com/contactus.php](https://www.taxifarefinder.com/contactus.php).


## Installation

This package can be installed through composer.

```bash
composer require tomoehlrich/taxifarefinder
```
## Laravel installation

This package can be used for Laravel and non-Laravel projects.

In Laravel 5.5+ the package will make use of the Laravel autoregister feature. In older versions of Laravel you have to manually install the package's service provider and facade.

```php
// config/app.php
'providers' => [
    // ...
    TomOehlrich\TaxiFareFinder\TaxiFareFinderServiceProvider::class
];
```

```php
// config/app.php
'aliases' => [
	// ...
	'TaxiFareFinder' => TomOehlrich\TaxiFareFinder\Facades\TaxiFareFinder::class,
]
```

Next, you have to publish the config file:

```bash
php artisan vendor:publish --provider="TomOehlrich\TaxiFareFinder\TaxiFareFinderServiceProvider"
```

The config file contains only one option. Please make sure that your API key is available here.

```php
return [

    /*
    |--------------------------------------------------------------------------
    | TaxiFareFinder API Key
    |--------------------------------------------------------------------------
    |
    | The TaxiFareFinder API Key can be requested at
    | https://www.taxifarefinder.com/contactus.php
    |
    */

    'api_key' => env('TAXIFAREFINDER_API_KEY', ''),

];
```

## Usage

First create a new instance of the TaxiFareFinder class.

```php
$tff = new TaxiFareFinder('<YOUR API KEY>');

```

You can get the nearest city that is supported by the API:

```php
return $tff->getNearestCity(1.290270, 103.851959);

/*
  This function returns the following array:

  Array
    (
        [name] => Singapore
        [full_name] => Singapore, Singapore
        [handle] => Singapore
        [locale] => zh_SG
        [distance] => 7747
    )

```


You can get details about a taxi fare.

```php
return $tff->getTaxiFare(42.368025,-71.022155, 42.362571,-71.055543);

/*
  This function returns the following array:

  Array
    (
        [total_fare] => 20.61
        [initial_fare] => 2.6
        [metered_fare] => 10.42
        [tip_amount] => 2.69
        [tip_percentage] => 15
        [locale] => en_US
        [currency] => Array
            (
                [int_symbol] => USD
            )

        [rate_area] => Boston, MA
        [flat_rates] => Array
            (
            )

        [extra_charges] => Array
            (
                [0] => Array
                    (
                        [charge] => 2.25
                        [description] => Airport Fee
                    )

                [1] => Array
                    (
                        [charge] => 2.65
                        [description] => Harbor tunnel toll
                    )

            )

        [distance] => 4591.1
        [duration] => 544
    )

```


You can get a list of taxi companies in a city.

```php
return $tff->getTaxiCompanies('Ho-Chi-Minh-Vietnam');

/*
  This function returns the following array:

  Array
    (
        [businesses] => Array
            (
                [0] => Array
                    (
                        [name] => DichungTaxi
                        [phone] => 093-607-0416
                        [type] => taxi
                    )

            )

    )

```

In Laravel you can just call

```php
return TaxiFareFinder::getNearestCity(1.290270, 103.851959);

```

To catch exceptions you should wrap methods in a try .. catch block.

```php
try {
    $fare = $tff->getTaxiFare(42.368025,-71.022155, 42.362571,-71.055543);
} catch(TomOehlrich\TaxiFareFinder\Exceptions\TffException $e) {
    return $e->getMessage();
} catch(\Exception $e) {
    return $e->getMessage();
}

```

This package comes with a couple of tests. Just enter your API key in tests/TestCase.php and run

```bash
composer test
```