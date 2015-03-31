<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * @Route("/admin/configuration")
 */
class ConfigController extends Controller
{
    /**
    * @Route("/", name="odysseus_admin_configuration")
    */
    public function indexAction($name="super")
    {
        return $this->render('OdysseusAdminBundle:Config:index.html.twig', array('name' => $name));
    }
	
    private function isConnected(){
            $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY');
    }
}
