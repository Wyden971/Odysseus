<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Image
 *
 * @ORM\Table(name="Image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image {

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
     * @ORM\Column(name="name", type="string", length=256, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=65535, nullable=false)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="text", length=65535, nullable=false)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="image")
     */
    private $article;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ArticleModel", mappedBy="image")
     */
    private $model;

    public function __toString() {
        return 'test';
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->article = new \Doctrine\Common\Collections\ArrayCollection();
        $this->model = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Image
     */
    public function setUrl($url) {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
     */
    public function setPath($path) {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Image
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * Add article
     *
     * @param \Odysseus\AdminBundle\Entity\Article $article
     * @return Image
     */
    public function addArticle(\Odysseus\AdminBundle\Entity\Article $article) {
        $this->article[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \Odysseus\AdminBundle\Entity\Article $article
     */
    public function removeArticle(\Odysseus\AdminBundle\Entity\Article $article) {
        $this->article->removeElement($article);
    }

    /**
     * Get article
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticle() {
        return $this->article;
    }

    /**
     * Add model
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $model
     * @return Image
     */
    public function addModel(\Odysseus\AdminBundle\Entity\ArticleModel $model) {
        $this->model[] = $model;

        return $this;
    }

    /**
     * Remove model
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $model
     */
    public function removeModel(\Odysseus\AdminBundle\Entity\ArticleModel $model) {
        $this->model->removeElement($model);
    }

    /**
     * Get model
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file = NULL;
    private $filenameForRemove;
    private $filenameForSave = NULL;
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->filenameForSave = NULL;
        if (null !== $this->file) {
            $extension = $this->file->guessExtension();
            if(!in_array(strtolower($extension), array('jpeg', 'jpg', 'png'))){
                $this->file = NULL;
                return;
            }
               
            $this->createdAt = new \DateTime();
            $this->setName($this->file->getClientOriginalName());
            $this->setPath($this->getFullName());
            $this->setUrl($this->getWebUrl());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->getFileName());

        unset($this->file);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove() {
        $this->filenameForRemove = $this->getPath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($this->filenameForRemove) {
            unlink($this->filenameForRemove);
        }
    }

    public function getFileName() {
        $filename = $this->filenameForSave;
        
        if($this->filenameForSave == NULL){
            $original = $this->file->getClientOriginalName();
            $extension = $this->file->guessExtension();
            if (!$extension) {
                $extension = 'bin';
            }
            $filename = md5(rand(0, 9999999)) . md5($original) . '.' . $extension;
            $this->filenameForSave = $filename;
        }
        
        return $filename;
    }

    public function getAbsolutePath() {
        return $this->getUploadRootDir() . '/';
    }

    public function getWebPath() {
        return $this->getUploadDir() . '/';
    }

    protected function getUploadRootDir() {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'uploads/images/';
    }
    
    protected function getFullName(){
        return $this->getAbsolutePath().$this->getFileName();
    }
    
    protected function getWebUrl(){
        return '/'.$this->getWebPath().$this->getFileName();
    }
}
