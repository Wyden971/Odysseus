<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Odysseus\AdminBundle\Entity\Order;
use Odysseus\AdminBundle\Form\OrderType;
use Odysseus\AdminBundle\Entity\OrderDetails;
use Odysseus\AdminBundle\Entity\UserInfos;

/**
 * @Route("/commande")
 */
class OrderController extends Controller {
    
    /**
     * @Route("/", name="odysseus_front_order_index")
     * 
     */
    public function indexAction(){
        return $this->redirect($this->generateURL('odysseus_front_cart_index'));
    }
    
    /**
     * @Route("/etape-1", name="odysseus_front_order_step1")
     */
    public function step1Action(Request $request) {
        if(!$request->request->has('shipping_method')){
            throw $this->createNotFoundException('Vous devez définir une méthode d\'expédition');
        }
        
        $shippingMethod = $this->getRepository('ShippingMethod')->find($request->request->get('shipping_method'));
        
        if(!$shippingMethod){
            throw $this->createNotFoundException('La méthode d\'expédition n\'existe pas');
        }
        
        $user=$this->getUser();
        $cart = $user->getActiveCart();
        
        $order = new Order();
        $form = $this->createForm(new OrderType(), $order, array(
            'method' => 'POST'
        ));
        
        $shipping = $this->getActualShippingDetails();
        $builling = $this->getActualBuillingDetails();
        
        $order->setShipping($shipping);
        $order->setBuilling($builling);
        $order->setUser($this->getUser());
        $order->setCreatedAt(new \DateTime());
        $order->setStatus($this->getDoctrine()->getRepository('OdysseusAdminBundle:OrderStatus')->find(1));
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            
            if($form->isValid()){
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
                
            }
        }
        
        $methods = array();
        foreach($this->getRepository('ShippingMethod')->findBy(array(), array('order'=>'ASC')) as $method){
            $total = 0;
            foreach($cart->getModel() as $model){
                $total += round($model->getShippingPrice($method)*100)/100;
            }
            $methods[] = (Object)array(
                'price' => $total,
                'data' => $method
            );
        }
        $paymentMethods = $this->getRepository('PaymentMethod')->findBy(array(), array('order'=>'ASC'));
        return $this->render('OdysseusFrontBundle:Order:index.html.twig', array(
            'form' => $form->createView(),
            'methods' => $methods,
            'paymentMethods' => $paymentMethods
        ));
    }
    
    private function getRepository($model){
        return $this->getDoctrine()->getRepository("OdysseusAdminBundle:$model");
    }
    
    private function getActualShippingDetails(){
        $shippingInfos = $this->getUser()->getDefaultInfos();
        $orderShipping = new OrderDetails();
        
        $orderShipping->setCompagny($shippingInfos->getCompany());
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
    
    private function getActualBuillingDetails(){
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
