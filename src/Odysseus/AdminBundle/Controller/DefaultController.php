<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin")
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="odysseus_admin_index")
     */
    public function indexAction() {
        return $this->redirect($this->generateURL('odysseus_admin_dashboard'));
    }

    /**
     * @Route("/dashboard", name="odysseus_admin_dashboard")
     */
    public function dashboardAction() {
        return $this->render('OdysseusAdminBundle:Dashboard:index.html.twig', array(
                    'lastProducts' => $this->getLastProducts(),
                    'lastUnvalidatedProducts' => $this->getLastUnvalidatedProducts(),
                    'lastValidatedProducts' => $this->getLastValidatedProducts(),
                    'lastOrders' => $this->getLastOrders(),
                    'lastUsers' => $this->getLastUsers()
        ));
    }
    
    function getLastProducts($nb = 5) {
        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel')->createQueryBuilder('am');

        $builder->setMaxResults($nb)
                ->orderBy('am.createdAt', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }
    
    function getLastUnvalidatedProducts($nb = 5) {
        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:Article')->createQueryBuilder('a');

        $builder->setMaxResults($nb)
                ->where('a.validatedAt is NULL')
                ->orderBy('a.createdAt', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }

    function getLastValidatedProducts($nb = 5) {
        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:Article')->createQueryBuilder('a');

        $builder->setMaxResults($nb)
                ->where('a.validatedAt IS NOT NULL')
                ->orderBy('a.createdAt', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }

    function getLastOrders($nb = 5) {
        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:Order')->createQueryBuilder('o');

        $builder->setMaxResults($nb)
                ->orderBy('o.createdAt', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }

    function getLastUsers($nb = 5) {
        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:User')->createQueryBuilder('u');

        $builder->setMaxResults($nb)
                ->orderBy('u.createdAt', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }

}
