<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/admin/order", name="odysseus_admin_order_root")
 */
class OrderController extends Controller {

    /**
     * @Route("/", name="odysseus_admin_order", defaults={"page"="1"})
     * @Route("/page/{page}", name="odysseus_admin_order_page", requirements={"page"="\d+"})
     */
    public function indexAction($page = 1) {
        $ppp = 10;
        $orders = $this->getOrders($page, $ppp);
        $count = $this->getOrdersCount();
        return $this->render('OdysseusAdminBundle:Order:index.html.twig', array(
                    'orders' => $orders,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count)
        ));
    }

    /**
     * @Route("/view/{id}", name="odysseus_admin_order_view", requirements={"id"="\d+"})
     */
    public function viewAction($id) {
        $order = $this->getOrder($id);
        if (!$order) {
            throw $this->createNotFoundException('La commande n\'existe pas');
        }

        
        return $this->render('OdysseusAdminBundle:Order:view.html.twig', array(
            'data' => $order
        ));
    }

 

    private function getOrders($page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:Order')->findBy(array(), null, $count, $page * $count);
    }

    private function getOrdersCount() {
        return $this->getDoctrine()->getEntityManager()->createQueryBuilder()->select('COUNT(a)')->from('OdysseusAdminBundle:Order', 'a')->getQuery()->getSingleScalarResult();
    }

    private function getPagination($page, $ppp, $count) {
        $min_page = $page - 3;
        if ($min_page < 1)
            $min_page = 1;

        $max_page = $min_page + 6;
        $total_page = ((int) ($count / $ppp)) + (($count % $ppp) == 0 ? 0 : 1);
        if ($max_page > $total_page)
            $max_page = $total_page;

        if ($min_page >= $max_page)
            return NULL;

        return (Object) array(
                    'min' => $min_page,
                    'max' => $max_page,
                    'page' => $page,
                    'ppp' => $ppp,
                    'total' => $total_page
        );
    }

    private function getOrder($id) {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:Order')->find($id);
    }

    private function removeOrder($id_or_order) {
        $order = $id_or_order;
        if (is_numeric($id_or_order))
            $order = $this->getOrder($id_or_order);

        if (!$order)
            return FALSE;

        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        try {
            $em->flush();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

}
