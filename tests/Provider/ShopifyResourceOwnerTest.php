<?php namespace Pizdata\Shopify\OAuth2\Client\Test\Provider;

use Mockery as m;
use PHPUnit\Framework\TestCase;

class ShopifyResourceOwnerTest extends TestCase
{

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner::__construct
     * @covers \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner::toArray
     */
    public function testConstruct()
    {
        $response = [
            'shop' => [
                'data' => 'data'
            ]
        ];

        $user = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner($response);

        $this->assertEquals($user->toArray(), $response['shop']);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner::getEmail
     */
    public function testEmailIsNullWithoutAnyAdditionalData()
    {
        $user = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner();

        $email = $user->getEmail();

        $this->assertNull($email);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner::getId
     */
    public function testResourceId()
    {
        $id = rand(1, 10000);
        $store = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner([
            'shop' => [
                'id' => $id
            ]
        ]);

        $this->assertEquals($store->getId(), $id);
    }

    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner::getDomain
     */
    public function testResourceDomain()
    {
        $domain = 'mock-domain.com';
        $store = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner([
            'shop' => [
                'domain' => $domain
            ]
        ]);

        $this->assertEquals($store->getDomain(), $domain);
    }


    /**
     *
     * @covers \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner::getName
     */
    public function testResourceName()
    {
        $name = 'mock-name';
        $store = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner([
            'shop' => [
                'name' => $name
            ]
        ]);

        $this->assertEquals($store->getName(), $name);
    }
}
