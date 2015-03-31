<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/produits/")
 */
class ArticleController extends Controller {

    /**
     * @Route("/", name="odysseus_front_product_details")
     */
    public function indexAction() {
        return $this->render('OdysseusFrontBundle:Article:details.html.twig');
    }

    public function getRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:Article');
    }
    
    public function getArticleModelRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel');
    }

    public function getCategoryRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleCategory');
    }

    public function getCategory($id) {
        return $this->getCategoryRepository()->find($id);
    }

    public function getCategoryByName($name) {
        return $this->getCategoryRepository()->findOneByUrl($name);
    }
}