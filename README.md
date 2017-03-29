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
$ composer require pizdata/oauth2-shopify
```

## Usage

``` php
$provider = new Pizdata\OAuth2\Client\Provider\Shopify([
    'clientId'                => '{shopify-client-id}',    // The client ID assigned to you by the Shopify
    'clientSecret'            => '{shopify-client-secret}',   // The client password assigned to you by the Shopify
    'redirectUri'             => 'http://localhost/callback', // The redirect URI assigned to you
    'store'                   => 'my-test-store', // The Store name
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Setting up scope
    $options = [
        'scope' => [
            'read_content', 'write_content',
            'read_themes', 'write_themes',
            'read_products', 'write_products',
            'read_customers', 'write_customers',
            'read_orders', 'write_orders',
            'read_draft_orders', 'write_draft_orders',
            'read_script_tags', 'write_script_tags',
            'read_fulfillments', 'write_fulfillments',
            'read_shipping', 'write_shipping',
            'read_analytics',
        ]
    ];
    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl($options);

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    
    exit('Invalid state');

} else {

    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        $store = $provider->getResourceOwner($accessToken);

        // Access to Store base information
        echo $store->getName();
        echo $store->getEmail();
        echo $store->getDomain();

        // Use this to interact with an API on the users behalf
        echo $token->getToken();

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        // Failed to get the access token or user details.
        exit($e->getMessage());

    }
}

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

[link-packagist]: https://packagist.org/packages/pizdata/oauth2-shopify
[link-travis]: https://travis-ci.org/pizdata/oauth2-shopify-php
[link-scrutinizer]: https://scrutinizer-ci.com/g/pizdata/oauth2-shopify-php/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/pizdata/oauth2-shopify-php
[link-downloads]: https://packagist.org/packages/pizdata/oauth2-shopify
[link-author]: https://github.com/sevastyanovio
[link-contributors]: ../../contributors
[link-oauth2-league]: https://github.com/thephpleague/oauth2-client
[link-oauth2-shopify]: https://help.shopify.com/api/getting-started/authentication/oauth
