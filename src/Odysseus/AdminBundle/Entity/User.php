<?php

namespace Odysseus\AdminBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Odysseus\AdminBundle\Entity\Cart;

/**
 * User
 *
 * @ORM\Table(name="User", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_2DA1797792FC23A8", columns={"username_canonical"}), @ORM\UniqueConstraint(name="UNIQ_2DA17977A0D96FBF", columns={"email_canonical"})}, indexes={@ORM\Index(name="fk_User_seller_infos1_idx", columns={"seller_infos_id"})})
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
    * @ORM\OneToMany(targetEntity="UserInfos", mappedBy="user", cascade={"persist", "remove"})
    */
    protected $infos;

    /**
     * @var \SellerInfos
     *
     * @ORM\ManyToOne(targetEntity="SellerInfos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seller_infos_id", referencedColumnName="id")
     * })
     */
    private $sellerInfos;

    /**
     * @var \Date
     *
     * @ORM\Column(name="birthdate", type="date", nullable=false)
     */
    private $birthdate;
    
    /**
     * @var \Boolean
     *
     * @ORM\Column(name="receive_special_offers", type="boolean", nullable=true)
     */
    private $receiveSpecialOffers = false;
    
    /**
     * @var \Date
     *
     * @ORM\Column(name="receive_partners_special_offers", type="boolean", nullable=true)
     */
    private $receivePartnersSpecialOffers = false;
    
    /**
    * @ORM\OneToMany(targetEntity="Order", mappedBy="user", cascade={"persist", "remove"})
    */
    protected $orders;
    
    /**
    * @ORM\OneToMany(targetEntity="Article", mappedBy="user", cascade={"persist", "remove"})
    */
    protected $articles;
    
    /**
    * @ORM\OneToMany(targetEntity="ArticleModel", mappedBy="user", cascade={"persist", "remove"})
    */
    protected $articleModels;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;
    
    /**
    * @ORM\OneToMany(targetEntity="Cart", mappedBy="user", cascade={"persist", "remove"})
    */
    protected $carts;
    
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
     * Set sellerInfos
     *
     * @param \Odysseus\AdminBundle\Entity\SellerInfos $sellerInfos
     * @return User
     */
    public function setSellerInfos(\Odysseus\AdminBundle\Entity\SellerInfos $sellerInfos = null)
    {
        $this->sellerInfos = $sellerInfos;

        return $this;
    }

    /**
     * Get sellerInfos
     *
     * @return \Odysseus\AdminBundle\Entity\SellerInfos 
     */
    public function getSellerInfos()
    {
        return $this->sellerInfos;
    }
    
    public function __toString(){
        return $this->getEmail();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->infos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add infos
     *
     * @param \Odysseus\AdminBundle\Entity\UserInfos $infos
     * @return User
     */
    public function addInfo(\Odysseus\AdminBundle\Entity\UserInfos $infos)
    {
        $this->infos[] = $infos;

        return $this;
    }

    /**
     * Remove infos
     *
     * @param \Odysseus\AdminBundle\Entity\UserInfos $infos
     */
    public function removeInfo(\Odysseus\AdminBundle\Entity\UserInfos $infos)
    {
        $this->infos->removeElement($infos);
    }

    /**
     * Get infos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInfos()
    {
        return $this->infos;
    }
    
    public function getMainInfos(){
        $infos = $this->getInfos();
        if(!empty($infos))
            return $infos[0];
        return NULL;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }


    /**
     * Set receiveSpecialOffers
     *
     * @param boolean $receiveSpecialOffers
     * @return User
     */
    public function setReceiveSpecialOffers($receiveSpecialOffers)
    {
        $this->receiveSpecialOffers = $receiveSpecialOffers;

        return $this;
    }

    /**
     * Get receiveSpecialOffers
     *
     * @return boolean 
     */
    public function getReceiveSpecialOffers()
    {
        return $this->receiveSpecialOffers;
    }

    /**
     * Set receivePartnersSpecialOffers
     *
     * @param boolean $receivePartnersSpecialOffers
     * @return User
     */
    public function setReceivePartnersSpecialOffers($receivePartnersSpecialOffers)
    {
        $this->receivePartnersSpecialOffers = $receivePartnersSpecialOffers;

        return $this;
    }

    /**
     * Get receivePartnersSpecialOffers
     *
     * @return boolean 
     */
    public function getReceivePartnersSpecialOffers()
    {
        return $this->receivePartnersSpecialOffers;
    }
    
    public function getFullName(){
        if($this->getDefaultInfos()){
            return $this->getDefaultInfos()->getFullName();
        }
        return '';
    }
    
    public function getDefaultInfos(){
        if(count($this->getInfos())!=0){
            $info = $this->infos[0];
            foreach($this->infos as $inf){
                if($inf->getIsDefault()){
                    $info = $inf;
                    break;
                }
            }
            return $info;
        }
        return NULL;
    }
    
    public function getBuillingInfos(){
        if(count($this->getInfos())!=0){
            
            $info = $this->infos[0];
            foreach($this->infos as $inf){
                if($inf->getIsBuilling()){
                    $info = $inf;
                    break;
                }
            }
            return $info;
        }
        return NULL;
    }

    /**
     * Add orders
     *
     * @param \Odysseus\AdminBundle\Entity\Order $orders
     * @return User
     */
    public function addOrder(\Odysseus\AdminBundle\Entity\Order $orders)
    {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param \Odysseus\AdminBundle\Entity\Order $orders
     */
    public function removeOrder(\Odysseus\AdminBundle\Entity\Order $orders)
    {
        $this->orders->removeElement($orders);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Add articles
     *
     * @param \Odysseus\AdminBundle\Entity\Article $articles
     * @return User
     */
    public function addArticle(\Odysseus\AdminBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \Odysseus\AdminBundle\Entity\Article $articles
     */
    public function removeArticle(\Odysseus\AdminBundle\Entity\Article $articles)
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

    /**
     * Add articleModels
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $articleModels
     * @return User
     */
    public function addArticleModel(\Odysseus\AdminBundle\Entity\ArticleModel $articleModels)
    {
        $this->articleModels[] = $articleModels;

        return $this;
    }

    /**
     * Remove articleModels
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $articleModels
     */
    public function removeArticleModel(\Odysseus\AdminBundle\Entity\ArticleModel $articleModels)
    {
        $this->articleModels->removeElement($articleModels);
    }

    /**
     * Get articleModels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticleModels()
    {
        return $this->articleModels;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
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
     * Add carts
     *
     * @param \Odysseus\AdminBundle\Entity\Cart $carts
     * @return User
     */
    public function addCart(\Odysseus\AdminBundle\Entity\Cart $carts)
    {
        $this->carts[] = $carts;

        return $this;
    }

    /**
     * Remove carts
     *
     * @param \Odysseus\AdminBundle\Entity\Cart $carts
     */
    public function removeCart(\Odysseus\AdminBundle\Entity\Cart $carts)
    {
        $this->carts->removeElement($carts);
    }

    /**
     * Get carts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCarts()
    {
        return $this->carts;
    }
    
    public function getActiveCart(){

        if(count($this->carts) == 0){
            $cart = new Cart();
            $cart->setIsVisible(true);
            $cart->setUser($this);
            $cart->setCreatedAt(new \DateTime());
            $cart->setModifiedAt(new \DateTime());
            $cart->setName('Mon premier panier');
            $this->carts[] = $cart;
            
            return $cart;
        }
        
        foreach($this->carts as $cart){
            if($cart->getIsVisible()){
                return $cart;
            }
        }
        return $this->carts[0];
    }
    
    public function removeActiveCart(){
        try{
            foreach($this->getCarts() as $cart)
                $this->removeCart($cart);
        }catch(Exception $e){
            return FALSE;
        }
        return TRUE;
    }
}
