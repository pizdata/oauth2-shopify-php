# Shopify OAuth 2.0 Client Provider

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

[Shopify OAuth 2.0][link-oauth2-shopify] Client Provider for [The PHP League OAuth2-Client][link-oauth2-league].

## Install

Via Composer

``` bash
$ composer require pizdata/oauth2-shopify-php
```

## Usage

``` php
$provider = new Pizdata\OAuth2\Client\Provider();
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email roman+gh@sevastyanov.io instead of using the issue tracker.

## Credits

- [Roman Sevastyanov][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/pizdata/oauth2-shopify-php.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/pizdata/oauth2-shopify-php/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/pizdata/oauth2-shopify-php.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/pizdata/oauth2-shopify-php.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pizdata/oauth2-shopify-php.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pizdata/oauth2-shopify-php
[link-travis]: https://travis-ci.org/pizdata/oauth2-shopify-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/pizdata/oauth2-shopify-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/pizdata/oauth2-shopify-php
[link-downloads]: https://packagist.org/packages/pizdata/oauth2-shopify-php
[link-author]: https://github.com/sevastyanovio
[link-contributors]: ../../contributors
[link-oauth2-league]: https://github.com/thephpleague/oauth2-client
[link-oauth2-shopify]: https://help.shopify.com/api/getting-started/authentication/oauth
