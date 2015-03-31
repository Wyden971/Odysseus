<?php

namespace Odysseus\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderArticle
 *
 * @ORM\Table(name="order_article", indexes={@ORM\Index(name="fk_order_article_order1_idx", columns={"order_id"}), @ORM\Index(name="fk_order_article_order_article_status1_idx", columns={"status_id"}), @ORM\Index(name="fk_order_article_article_model1_idx", columns={"model_id"})})
 * @ORM\Entity
 */
class OrderArticle
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
     * @var \ArticleModel
     *
     * @ORM\ManyToOne(targetEntity="ArticleModel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * })
     */
    private $model;

    /**
     * @var \Order
     *
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     * })
     */
    private $order;

    /**
     * @var \OrderArticleStatus
     *
     * @ORM\ManyToOne(targetEntity="OrderArticleStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $status;



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
     * Set model
     *
     * @param \Odysseus\AdminBundle\Entity\ArticleModel $model
     * @return OrderArticle
     */
    public function setModel(\Odysseus\AdminBundle\Entity\ArticleModel $model = null)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return \Odysseus\AdminBundle\Entity\ArticleModel 
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set order
     *
     * @param \Odysseus\AdminBundle\Entity\Order $order
     * @return OrderArticle
     */
    public function setOrder(\Odysseus\AdminBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Odysseus\AdminBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set status
     *
     * @param \Odysseus\AdminBundle\Entity\OrderArticleStatus $status
     * @return OrderArticle
     */
    public function setStatus(\Odysseus\AdminBundle\Entity\OrderArticleStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Odysseus\AdminBundle\Entity\OrderArticleStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
