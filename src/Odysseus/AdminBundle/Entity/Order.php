<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Order
 *
 * @ORM\Table(name="`order`", indexes={@ORM\Index(name="fk_order_User1_idx", columns={"User_id"}), @ORM\Index(name="fk_order_payment_method1_idx", columns={"payment_method_id"}), @ORM\Index(name="fk_order_order_details1_idx", columns={"builling_id"}), @ORM\Index(name="fk_order_order_details2_idx", columns={"shipping_id"}), @ORM\Index(name="fk_order_shipping_method1_idx", columns={"shipping_method_id"}), @ORM\Index(name="fk_order_order_status1_idx", columns={"status_id"})})
 * @ORM\Entity
 */
class Order
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validated_at", type="datetime", nullable=true)
     */
    private $validatedAt;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \PaymentMethod
     *
     * @ORM\ManyToOne(targetEntity="PaymentMethod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="payment_method_id", referencedColumnName="id")
     * })
     */
    private $paymentMethod;

    /**
     * @var \OrderDetails
     *
     * @ORM\ManyToOne(targetEntity="OrderDetails", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="builling_id", referencedColumnName="id")
     * })
     */
    private $builling;

    /**
     * @var \OrderDetails
     *
     * @ORM\ManyToOne(targetEntity="OrderDetails", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_id", referencedColumnName="id")
     * })
     */
    private $shipping;

    /**
     * @var \ShippingMethod
     *
     * @ORM\ManyToOne(targetEntity="ShippingMethod")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_method_id", referencedColumnName="id")
     * })
     */
    private $shippingMethod;

    /**
     * @var \OrderStatus
     *
     * @ORM\ManyToOne(targetEntity="OrderStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;


    /**
    * @ORM\OneToMany(targetEntity="OrderArticle", mappedBy="order", cascade={"persist", "remove"})
    */
    protected $articles;
    
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Order
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set validatedAt
     *
     * @param \DateTime $validatedAt
     * @return Order
     */
    public function setValidatedAt($validatedAt)
    {
        $this->validatedAt = $validatedAt;

        return $this;
    }

    /**
     * Get validatedAt
     *
     * @return \DateTime 
     */
    public function getValidatedAt()
    {
        return $this->validatedAt;
    }

    /**
     * Set user
     *
     * @param \Odysseus\AdminBundle\Entity\User $user
     * @return Order
     */
    public function setUser(\Odysseus\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Odysseus\AdminBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set paymentMethod
     *
     * @param \Odysseus\AdminBundle\Entity\PaymentMethod $paymentMethod
     * @return Order
     */
    public function setPaymentMethod(\Odysseus\AdminBundle\Entity\PaymentMethod $paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return \Odysseus\AdminBundle\Entity\PaymentMethod 
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set builling
     *
     * @param \Odysseus\AdminBundle\Entity\OrderDetails $builling
     * @return Order
     */
    public function setBuilling(\Odysseus\AdminBundle\Entity\OrderDetails $builling = null)
    {
        $this->builling = $builling;

        return $this;
    }

    /**
     * Get builling
     *
     * @return \Odysseus\AdminBundle\Entity\OrderDetails 
     */
    public function getBuilling()
    {
        return $this->builling;
    }

    /**
     * Set shipping
     *
     * @param \Odysseus\AdminBundle\Entity\OrderDetails $shipping
     * @return Order
     */
    public function setShipping(\Odysseus\AdminBundle\Entity\OrderDetails $shipping = null)
    {
        $this->shipping = $shipping;

        return $this;
    }

    /**
     * Get shipping
     *
     * @return \Odysseus\AdminBundle\Entity\OrderDetails 
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Set shippingMethod
     *
     * @param \Odysseus\AdminBundle\Entity\ShippingMethod $shippingMethod
     * @return Order
     */
    public function setShippingMethod(\Odysseus\AdminBundle\Entity\ShippingMethod $shippingMethod = null)
    {
        $this->shippingMethod = $shippingMethod;

        return $this;
    }

    /**
     * Get shippingMethod
     *
     * @return \Odysseus\AdminBundle\Entity\ShippingMethod 
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    /**
     * Set status
     *
     * @param \Odysseus\AdminBundle\Entity\OrderStatus $status
     * @return Order
     */
    public function setStatus(\Odysseus\AdminBundle\Entity\OrderStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Odysseus\AdminBundle\Entity\OrderStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add articles
     *
     * @param \Odysseus\AdminBundle\Entity\OrderArticle $articles
     * @return Order
     */
    public function addArticle(\Odysseus\AdminBundle\Entity\OrderArticle $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Odysseus\AdminBundle\Entity\OrderArticle $articles
     */
    public function removeArticle(\Odysseus\AdminBundle\Entity\OrderArticle $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
    
    
    public function getTotal(){
        $total = 0;
        foreach($this->articles as $article){
            $total+=$article->getModel()->getPrice();
            $total+=$article->getModel()->getShippingPrice($this->shippingMethod);
        }
        return $total;
    }
    
    public function getShippingCost(){
        $total = 0;
        foreach($this->articles as $article){
            $total+=$article->getModel()->getShippingPrice($this->shippingMethod);
        }
        return $total;
    }
}
