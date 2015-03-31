<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 *
 * @ORM\Table(name="page", indexes={@ORM\Index(name="fk_page_page_status1_idx", columns={"status_id"}), @ORM\Index(name="fk_page_page1_idx", columns={"parent_id"})})
 * @ORM\Entity
 */
class Page
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
     * @ORM\Column(name="title", type="string", length=45, nullable=false)
     */
    private $title;
	
    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=65535, nullable=false)
     */
    private $url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

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
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=256, nullable=true)
     */
    private $template = 'OdysseusFrontBundle:Page:index.html.twig';

    /**
     * @var \PageStatus
     *
     * @ORM\ManyToOne(targetEntity="PageStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var \Page
     *
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $parent;


	/**
	 * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
	 */
    private $children;
    
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
     * Set title
     *
     * @param string $title
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
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
     * @return Page
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
     * Set template
     *
     * @param string $template
     * @return Page
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string 
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set status
     *
     * @param \Odysseus\AdminBundle\Entity\PageStatus $status
     * @return Page
     */
    public function setStatus(\Odysseus\AdminBundle\Entity\PageStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Odysseus\AdminBundle\Entity\PageStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set parent
     *
     * @param \Odysseus\AdminBundle\Entity\Page $parent
     * @return Page
     */
    public function setParent(\Odysseus\AdminBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Odysseus\AdminBundle\Entity\Page 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add children
     *
     * @param \Odysseus\AdminBundle\Entity\Page $children
     * @return Page
     */
    public function addChild(\Odysseus\AdminBundle\Entity\Page $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Odysseus\AdminBundle\Entity\Page $children
     */
    public function removeChild(\Odysseus\AdminBundle\Entity\Page $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
}
