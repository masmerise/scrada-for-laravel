<p align="center">
<img src="https://www.scrada.be/wp-content/uploads/2023/10/ScradaLogoWebsite.png" alt="Scrada PHP SDK" height="50">
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1969px-Laravel.svg.png" alt="PHP" height="50">
</p>

<p align="center">
<a href="https://github.com/masmerise/scrada-for-laravel/actions"><img src="https://github.com/masmerise/scrada-for-laravel/actions/workflows/test.yml/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/masmerise/scrada-for-laravel"><img src="https://img.shields.io/packagist/dt/masmerise/scrada-for-laravel" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/masmerise/scrada-for-laravel"><img src="https://img.shields.io/packagist/v/masmerise/scrada-for-laravel" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/masmerise/scrada-for-laravel"><img src="https://img.shields.io/packagist/l/masmerise/scrada-for-laravel" alt="License"></a>
</p>

## Laravel adapter for the Scrada SDK

This package provides convenient access to the [Scrada SDK](https://github.com/masmerise/scrada-php-sdk) using the Laravel framework.

## Installation

You can install the package via [composer](https://getcomposer.org):

```bash
composer require masmerise/scrada-for-laravel
```

After that, define your `scrada` credentials inside the `config/services.php` configuration file:

```php
'scrada' => [
    'api_key' => env('SCRADA_API_KEY'),
    'password' => env('SCRADA_PASSWORD'),
],
```

## Usage

```php
$company = scrada()->company->get($id);
```

```php
$scrada = app('scrada');

$company = $scrada->company->get($id);
```

```php
use Scrada\Company\Type\Primitive\CompanyId;
use Scrada\Scrada;

final readonly class CompanyController
{
    private function __construct(private Scrada $scrada) {}
    
    public function show(string $id): void
    {
        $id = CompanyId::fromString($id);
    
        return view('company.show', [
            'company' => $this->scrada->company->get($id),
        ]);
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email support@masmerise.be instead of using the issue tracker.

## Credits

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.