<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Odysseus\AdminBundle\Entity\Order;
use Odysseus\AdminBundle\Form\OrderType;
use Odysseus\AdminBundle\Entity\OrderDetails;
use Odysseus\AdminBundle\Entity\UserInfos;
use Odysseus\AdminBundle\Entity\OrderArticle;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * @Route("/commande")
 */
class OrderController extends Controller {

    private $serializer;

    function __construct() {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/", name="odysseus_front_order_index")
     */
    public function indexAction(Request $request) {
        if (!$request->request->has('shipping_method')) {
            return $this->redirect($this->generateURL('odysseus_front_cart_index'));
        }

        $shippingMethod = $this->getRepository('ShippingMethod')->find($request->request->get('shipping_method'));

        if (!$shippingMethod) {
            return $this->redirect($this->generateURL('odysseus_front_cart_index'));
        }

        $order = new Order();
        $order->setShippingMethod($shippingMethod);
        $order->setPaymentMethod($this->getDefaultPaymentMethod());
        $order->setBuilling($this->getActualBuillingDetails());
        $order->setShipping($this->getActualShippingDetails());
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTime());
        $order->setStatus($this->getDefaultOrderStatus());

        $cart = $this->getUser()->getActiveCart();

        foreach ($cart->getModel() as $model) {
            $orderArticle = new OrderArticle();
            $orderArticle->setModel($model);
            $orderArticle->setStatus($this->getDoctrine()->getRepository('OdysseusAdminBundle:OrderArticleStatus')->find(1));
            $orderArticle->setOrder($order);

            $order->addArticle($orderArticle);
        }


        $form = $this->createForm(new OrderType(), $order);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
            }
        }
        return $this->render('OdysseusFrontBundle:Order:index.html.twig', array(
                    'form' => $form->createView(),
                    'methods' => $this->getShippingMethods($cart),
                    'paymentMethods' => $this->getPaymentMethods(),
                    'order' => $order
        ));
    }

    /**
     * @Route("/pdf/{id}", name="odysseus_front_order_pdf", requirements={"id"="\d+"})
     */
    public function pdfAction($id) {
        $user = $this->getUser();
        $commande = $this->getRepository('Order')
                ->createQueryBuilder('o')
                ->select('o')
                ->where('o.user = ' . $user->getId())
                ->andWhere('o.id = ' . $id)
                ->getQuery()
                ->getSingleResult();
        if (empty($commande)) {
            throw $this->createNotFoundException();
        }
        $html = $this->renderView('OdysseusFrontBundle:Order:pdf.html.twig', array(
            'commande' => $commande
        ));
        #return $this->render('OdysseusFrontBundle:Order:pdf.html.twig', array(
        #    'commande' => $commande
        #));
        return new Response(
                $this->get('knp_snappy.pdf')->generateFromHtml($html, '/symfony/file2.pdf'), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="file.pdf"'
                )
        );
    }

    private function getShippingMethods($cart) {
        $methods = array();
        $data = $this->getRepository('ShippingMethod')->findBy(array(), array(
            'order' => 'ASC'
        ));
        foreach ($data as $method) {
            $total = 0;
            foreach ($cart->getModel() as $model) {
                $total += round($model->getShippingPrice($method) * 100) / 100;
            }
            $methods[] = (Object) array(
                        'price' => $total,
                        'data' => $method
            );
        }
        return $methods;
    }

    private function getPaymentMethods() {
        return $this->getRepository('PaymentMethod')->findBy(array(), array(
                    'order' => 'ASC'
        ));
    }

    private function getDefaultPaymentMethod() {
        return $this->getRepository('PaymentMethod')->find(1);
    }

    private function getDefaultOrderStatus() {
        return $this->getRepository('OrderStatus')->find(1);
    }

    private function getRepository($model) {
        return $this->getDoctrine()->getRepository("OdysseusAdminBundle:$model");
    }

    private function getActualShippingDetails() {
        $shippingInfos = $this->getUser()->getDefaultInfos();
        $orderShipping = new OrderDetails();

        $orderShipping->setCompany($shippingInfos->getCompany());
        $orderShipping->setFirstName($shippingInfos->getFirstName());
        $orderShipping->setLastName($shippingInfos->getLastName());
        $orderShipping->setAddress1($shippingInfos->getAddress1());
        $orderShipping->setAddress2($shippingInfos->getAddress2());
        $orderShipping->setZipCode($shippingInfos->getZipCode());
        $orderShipping->setCity($shippingInfos->getCity());
        $orderShipping->setCountry($shippingInfos->getCountry());
        $orderShipping->setTelephone($shippingInfos->getMobilePhone());

        return $orderShipping;
    }

    private function getActualBuillingDetails() {
        $buillingInfos = $this->getUser()->getBuillingInfos();

        $orderBuilling = new OrderDetails();

        $orderBuilling->setCompany($buillingInfos->getCompany());
        $orderBuilling->setFirstName($buillingInfos->getFirstName());
        $orderBuilling->setLastName($buillingInfos->getLastName());
        $orderBuilling->setAddress1($buillingInfos->getAddress1());
        $orderBuilling->setAddress2($buillingInfos->getAddress2());
        $orderBuilling->setZipCode($buillingInfos->getZipCode());
        $orderBuilling->setCity($buillingInfos->getCity());
        $orderBuilling->setCountry($buillingInfos->getCountry());
        $orderBuilling->setTelephone($buillingInfos->getMobilePhone());

        return $orderBuilling;
    }

}
