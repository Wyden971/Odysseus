<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/commandes")
 */
class OrderController extends Controller
{
    /**
	 * @Route("/", name="odysseus_front_order_index")
     */
    public function indexAction()
    {
        return $this->render('OdysseusFrontBundle:Order:index.html.twig');
	}
	
	/**
	 * @Route("/mon-panier", name="odysseus_front_order_cart")
     */
    public function cartAction()
    {
        return $this->render('OdysseusFrontBundle:Order:cart.html.twig');
	}
}
