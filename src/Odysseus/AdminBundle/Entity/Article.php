<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="fk_article_User_idx", columns={"User_id"}), @ORM\Index(name="fk_article_article_category1_idx", columns={"category_id"})})
 * @ORM\Entity
 */
class Article
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
     * @ORM\Column(name="name", type="string", length=256, nullable=false)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=100, nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_at", type="datetime", nullable=false)
     */
    private $modifiedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="validated_at", type="datetime", nullable=true)
     */
    private $validatedAt;

    /**
     * @var \ArticleCategory
     *
     * @ORM\ManyToOne(targetEntity="ArticleCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="User_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Image", inversedBy="article")
     * @ORM\JoinTable(name="article_has_Image",
     *   joinColumns={
     *     @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="Image_id", referencedColumnName="id")
     *   }
     * )
     */
    private $image;
    
    /**
    * @ORM\OneToMany(targetEntity="ArticleModel", mappedBy="article", cascade={"persist", "remove"})
    */
    protected $models;
    
    public function __toString(){
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->image = new \Doctrine\Common\Collections\ArrayCollection();
        $this->models = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Article
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
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Article
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
     * @return Article
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
     * Set validatedAt
     *
     * @param \DateTime $validatedAt
     * @return Article
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
     * Set category
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleCategory $category
     * @return Article
     */
    public function setCategory(\Odysseus\AdminBundle\Entity\ArticleCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Odysseus\AdminBundle\Entity\ArticleCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set user
     *
     * @param \Odysseus\AdminBundle\Entity\User $user
     * @return Article
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
     * Add image
     *
     * @param \Odysseus\AdminBundle\Entity\Image $image
     * @return Article
     */
    public function addImage(\Odysseus\AdminBundle\Entity\Image $image)
    {
        $this->image[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \Odysseus\AdminBundle\Entity\Image $image
     */
    public function removeImage(\Odysseus\AdminBundle\Entity\Image $image)
    {
        $this->image->removeElement($image);
    }

    /**
     * Get image
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add models
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $models
     * @return Article
     */
    public function addModel(\Odysseus\AdminBundle\Entity\ArticleModel $models)
    {
        $this->models[] = $models;

        return $this;
    }

    /**
     * Remove models
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $models
     */
    public function removeModel(\Odysseus\AdminBundle\Entity\ArticleModel $models)
    {
        $this->models->removeElement($models);
    }

    /**
     * Get models
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * Set brand
     *
     * @param string $brand
     * @return Article
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string 
     */
    public function getBrand()
    {
        return $this->brand;
    }
}
