<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShippingMethod
 *
 * @ORM\Table(name="shipping_method")
 * @ORM\Entity
 */
class ShippingMethod
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price_per_weight", type="float", precision=10, scale=0, nullable=false)
     */
    private $pricePerWeight = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="price_per_m3", type="string", length=45, nullable=true)
     */
    private $pricePerM3;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ShippingMethod
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pricePerWeight
     *
     * @param float $pricePerWeight
     * @return ShippingMethod
     */
    public function setPricePerWeight($pricePerWeight)
    {
        $this->pricePerWeight = $pricePerWeight;

        return $this;
    }

    /**
     * Get pricePerWeight
     *
     * @return float 
     */
    public function getPricePerWeight()
    {
        return $this->pricePerWeight;
    }

    /**
     * Set pricePerM3
     *
     * @param string $pricePerM3
     * @return ShippingMethod
     */
    public function setPricePerM3($pricePerM3)
    {
        $this->pricePerM3 = $pricePerM3;

        return $this;
    }

    /**
     * Get pricePerM3
     *
     * @return string 
     */
    public function getPricePerM3()
    {
        return $this->pricePerM3;
    }
}
