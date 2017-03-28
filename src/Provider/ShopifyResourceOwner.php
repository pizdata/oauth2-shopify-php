<?php namespace Pizdata\OAuth2\Client\Provider;

use League\OAuth2\Client\Tool\ArrayAccessorTrait;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ShopifyResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array  $response
     */
    public function __construct(array $response = array())
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'shop.id');
    }

    /**
     * Get resource owner email
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->getValueByKey($this->response, 'shop.email');
    }

    /**
     * Get resource owner name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->getValueByKey($this->response, 'shop.name');
    }

    /**
     * Get resource owner domain
     *
     * @return string|null
     */
    public function getDomain()
    {
        return $this->getValueByKey($this->response, 'shop.domain');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getValueByKey($this->response, 'shop');
    }
}