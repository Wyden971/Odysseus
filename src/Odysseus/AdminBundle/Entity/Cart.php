<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart", indexes={@ORM\Index(name="fk_cart_User1_idx", columns={"User_id"})})
 * @ORM\Entity
 */
class Cart
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
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=true)
     */
    private $modifiedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_visible", type="integer", nullable=false)
     */
    private $isVisible = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="carts", cascade={"persist", "remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ArticleModel", inversedBy="cart")
     * @ORM\JoinTable(name="cart_has_article_model",
     *   joinColumns={
     *     @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     *   }
     * )
     */
    private $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * @return Cart
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
     * Set modifiedAt
     *
     * @param \DateTime $modifiedAt
     * @return Cart
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    /**
     * Get modifiedAt
     *
     * @return \DateTime 
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Cart
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
     * Set isVisible
     *
     * @param integer $isVisible
     * @return Cart
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    /**
     * Get isVisible
     *
     * @return integer 
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * Set user
     *
     * @param \Odysseus\AdminBundle\Entity\User $user
     * @return Cart
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
     * Add model
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $model
     * @return Cart
     */
    public function addModel(\Odysseus\AdminBundle\Entity\ArticleModel $model)
    {
        $this->model[] = $model;

        return $this;
    }

    /**
     * Remove model
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $model
     */
    public function removeModel(\Odysseus\AdminBundle\Entity\ArticleModel $model)
    {
        $this->model->removeElement($model);
    }

    /**
     * Get model
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModel()
    {
        return $this->model;
    }
}
