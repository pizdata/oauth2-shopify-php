<?php namespace Pizdata\OAuth2\Client\Provider;

use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;
use Pizdata\OAuth2\Client\Exception\ShopifyIdentityProviderException;

class Shopify extends AbstractProvider
{
	/**
     * Production Shopify domain.
     *
     * @const string
     */
	const BASE_SHOPIFY_DOMAIN = 'myshopify.com';

	/**
     * The store name.
     *
     * @var string
     */
	public $store = '';

	/**
	 * @param array $options
	 * @param array $collaborators
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($options = [], array $collaborators = [])
	{
		parent::__construct($options, $collaborators);

		if (empty($options['store'])) {
			$message = 'The "store" option not set. Please set a Store name.';
			throw new \InvalidArgumentException($message);
		}

		$this->store = $options['store'];
	}

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
	public function getBaseAuthorizationUrl()
	{
		return sprintf('https://%s.%s/admin/oauth/authorize', $this->store, self::BASE_SHOPIFY_DOMAIN);
	}

    /**
     * Get access token url to retrieve token
     *
     * @param  array $params
     *
     * @return string
     */
	public function getBaseAccessTokenUrl(array $params)
	{
		return sprintf('https://%s.%s/admin/oauth/access_token', $this->store, self::BASE_SHOPIFY_DOMAIN);
	}

    /**
     * Get provider url to fetch user details
     *
     * @param  AccessToken $token
     *
     * @return string
     */
	public function getResourceOwnerDetailsUrl(AccessToken $token)
	{
		// code
	}

	/**
	 * Get the default scopes used by this provider.
	 *
	 * @link https://help.shopify.com/api/getting-started/authentication/oauth#scopes
	 * @return array
	 */
	protected function getDefaultScopes()
	{
		return [
			'read_orders',
			'read_products'
		];
	}

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array $data
     * @return void
     */
	protected function checkResponse(ResponseInterface $response, $data)
	{
		if (!empty($data['errors'])) {
			throw new ShopifyIdentityProviderException($data['errors'], 0);
        }
	}

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
	protected function createResourceOwner(array $response, AccessToken $token)
	{
		$store = new ShopifyResourceOwner($response);

		return $store;
	}
}