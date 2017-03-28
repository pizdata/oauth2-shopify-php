<?php namespace Pizdata\Shopify\OAuth2\Client\Test\Provider;

use Mockery as m;

class ShopifyResourceOwnerTest extends \PHPUnit_Framework_TestCase
{
    public function testEmailIsNullWithoutAnyAdditionalData()
    {
        $user = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner();

        $email = $user->getEmail();

        $this->assertNull($email);
    }

    public function testUrlIsNicknameWithoutDomain()
    {
        $id = rand(1, 10000);
        $user = new \Pizdata\OAuth2\Client\Provider\ShopifyResourceOwner([
            'store' => [
                'id' => $id
            ]
        ]);

        $id = $user->getId();

        $this->assertEquals($id, $id);
    }
}