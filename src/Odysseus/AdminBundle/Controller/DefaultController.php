<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
* @Route("/admin")
*/
class DefaultController extends Controller
{
    /**
	* @Route("/", name="odysseus_admin_index")
	*/
    public function indexAction()
    {
        return $this->redirect($this->generateURL('odysseus_admin_dashboard'));
    }
    
    /**
	* @Route("/dashboard", name="odysseus_admin_dashboard")
	*/
    public function dashboardAction()
    {
        return $this->render('OdysseusAdminBundle:Dashboard:index.html.twig');
    }
}
