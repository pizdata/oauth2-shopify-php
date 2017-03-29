<?php namespace Pizdata\Shopify\OAuth2\Client\Test\Provider;

use League\OAuth2\Client\Tool\QueryBuilderTrait;
use Mockery as m;

class ShopifyTest extends \PHPUnit_Framework_TestCase
{
    use QueryBuilderTrait;

    protected $provider;

    protected function setUp()
    {
        $this->provider = new \Pizdata\OAuth2\Client\Provider\Shopify([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
            'shop' => 'mock_domain',
        ]);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    /**
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::__construct
     * @expectExceptionMessage 'The "shop" option not set. Please set a Shop name.'
     * @expectedException \InvalidArgumentException
     */
    public function testNoStoreName()
    {
        $provider = new \Pizdata\OAuth2\Client\Provider\Shopify([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getDefaultScopes
     */
    public function testDefaultScope()
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertRegexp('/scope=read_orders,read_products/', urldecode($uri['query']));
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getBaseAuthorizationUrl
     */
    public function testGetAuthorizationUrl()
    {
        $url = $this->provider->getBaseAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('/admin/oauth/authorize', $uri['path']);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getBaseAccessTokenUrl
     */
    public function testGetBaseAccessTokenUrl()
    {
        $params = [];

        $url = $this->provider->getBaseAccessTokenUrl($params);
        $uri = parse_url($url);

        $this->assertEquals('/admin/oauth/access_token', $uri['path']);
    }

    /**
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::checkResponse
     * @expectedException \Pizdata\OAuth2\Client\Exception\ShopifyIdentityProviderException
     */
    public function testWrongResponse400Code()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');

        $response->shouldReceive('getBody')->andReturn(
            '"Wrong data"'
        );
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(400);
        $response->shouldReceive('getReasonPhrase')->andReturn('Wrong data');

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        return $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    /**
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::checkResponse
     * @expectedException \Pizdata\OAuth2\Client\Exception\ShopifyIdentityProviderException
     */
    public function testWrongResponseErrors()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');

        $response->shouldReceive('getBody')->andReturn(
            '{"errors":"Error"}'
        );
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(401);
        $response->shouldReceive('getReasonPhrase')->andReturn('Wrong data');

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        return $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getResourceOwnerDetailsUrl
     */
    public function testResourceOwnerDetailsUrl()
    {
        $uri = $this->provider->getResourceOwnerDetailsUrl($this->getToken());
        $uri = parse_url($uri);

        $this->assertEquals('/admin/shop.json', $uri['path']);
    }

    protected function getToken()
    {
        $response = m::mock('Psr\Http\Message\ResponseInterface');

        $response->shouldReceive('getBody')->andReturn(
            '{"access_token":"mock_access_token", "scope":"read_orders,read_products", "token_type":"bearer"}'
        );
        $response->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $response->shouldReceive('getStatusCode')->andReturn(200);

        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')->times(1)->andReturn($response);
        $this->provider->setHttpClient($client);

        return $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::checkResponse
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getAccessToken
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getDefaultScopes
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getResourceOwner
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::createResourceOwner
     * @covers \Pizdata\OAuth2\Client\Provider\Shopify::getResourceOwnerDetailsUrl
     */
    public function testUserData()
    {
        $id = rand(1000,9999);
        $name = uniqid();
        $email = uniqid();
        $postResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $postResponse->shouldReceive('getBody')->andReturn('access_token=mock_access_token&expires=3600&refresh_token=mock_refresh_token&otherKey={1234}');
        $postResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'application/x-www-form-urlencoded']);
        $postResponse->shouldReceive('getStatusCode')->andReturn(200);
        $userResponse = m::mock('Psr\Http\Message\ResponseInterface');
        $userResponse->shouldReceive('getBody')->andReturn(
            '{"shop": {"id": '.$id.',"name": "'.$name.'","email": "'.$email.'"}}'
        );
        $userResponse->shouldReceive('getHeader')->andReturn(['content-type' => 'json']);
        $userResponse->shouldReceive('getStatusCode')->andReturn(200);
        $client = m::mock('GuzzleHttp\ClientInterface');
        $client->shouldReceive('send')
            ->times(2)
            ->andReturn($postResponse, $userResponse);
        $this->provider->setHttpClient($client);

        $token = $this->provider->getAccessToken('authorization_code', ['code' => 'mock_authorization_code']);
        $store = $this->provider->getResourceOwner($token);
        $storeArray = $store->toArray();

        $this->assertEquals(get_class($store), 'Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner');

        $this->assertEquals($id, $store->getId());
        $this->assertEquals($name, $store->getName());
        $this->assertEquals($email, $store->getEmail());

        $this->assertEquals($id, $storeArray['id']);
        $this->assertEquals($name, $storeArray['name']);
        $this->assertEquals($email, $storeArray['email']);
    }

    public function testGetAccessToken()
    {
        $token = $this->getToken();
        $this->assertEquals('mock_access_token', $token->getToken());
        $this->assertNull($token->getExpires());
        $this->assertNull($token->getRefreshToken());
        $this->assertNull($token->getResourceOwnerId());
    }
}
