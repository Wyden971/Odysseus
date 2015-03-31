<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/page")
 */
class PageController extends Controller {

    /**
     * @Route("/", name="odysseus_admin_page", defaults={"page"="1"})
     */
    public function indexAction() {
        $pages = $this->getRootPages();
        $count = $this->getPagesCount();
        return $this->render('OdysseusAdminBundle:Page:index.html.twig', array(
                    'pages' => $pages,
                    'count' => $count,
        ));
    }

    /**
     * @Route("/update/{id}", name="odysseus_admin_page_update", requirements={"id"="\d+"})
     */
    public function updateAction($id) {
        $page = $this->getPageEntity($id);
        if (!$page) {
            return $this->createNotFoundException('La page n\'existe pas');
        }


        return $this->render('OdysseusAdminBundle:Page:update.html.twig', array(
                    'data' => $page
        ));
    }

    /**
     * @Route("/delete/{id}", name="odysseus_admin_page_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id) {
        $page = $this->getPageEntity($id);
        if (!$page) {
            return $this->createNotFoundException('La page n\'existe pas');
        }

        $this->removePage($page);

        return $this->redirect($this->generateURL('odysseus_admin_page'));
    }

    private function getRootPages() {
        return $this->getRepository()->findBy(array('parent'=>NULL));
    }

    private function getRepository(){
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:Page');
    }
    
    private function getPagesCount() {
        return $this->getRepository()
                ->createQueryBuilder('p')
                ->select('COUNT(p)')
                ->where('p.parent is NULL')
                ->getQuery()
                ->getSingleScalarResult();
    }


    private function getPageEntity($id) {
        return $this->getRepository()->find($id);
    }

  

}
