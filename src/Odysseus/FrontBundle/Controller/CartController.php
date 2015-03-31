<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * @Route("/mon-panier")
 */
class CartController extends Controller {

    /**
     * @Route("/", name="odysseus_front_cart_index")
     */
    public function indexAction() {
        $user=$this->getUser();
        $cart = $user->getActiveCart();
        $methods = array();
        $totalPrice = 0;
        $totalPriceWithoutShipping = 0;
        
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
        
        foreach($cart->getModel() as $model){
            $totalPriceWithoutShipping+=$model->getPrice();
        }
        
        $shippingPrice = $methods[0]->price;
        
        
        
        return $this->render('OdysseusFrontBundle:Cart:index.html.twig', array(
            'methods' => $methods,
            'shippingPrice' => $shippingPrice,
            'totalPrice' => $totalPriceWithoutShipping + $shippingPrice,
            'totalPriceWithoutShipping' => $totalPriceWithoutShipping
        ));
    }
    
    /**
     * @Route("/a/{product_name}-{product_id}", name="odysseus_front_cart_add", requirements={"product_name"=".+", "product_id"="\d+"})
     */
    public function addAction(Request $request, $product_name, $product_id){
        $product = $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel')->find($product_id);
        if(!$product){
            throw $this->createNotFoundException();
        }
        
        $user=$this->getUser();
        $cart = $user->getActiveCart();
        
        $cart->addModel($product);
        $cart->setModifiedAt(new \DateTime());
        try{
            $this->persistCart($cart);
        }
        catch(UniqueConstraintViolationException $exception){
            $this->addFlashBag('odysseus_front_cart', 'Le produit a déjà été ajouté à votre panier');
            return $this->redirect($request->headers->get('referer'));
        }
        
        $this->addFlashBag('odysseus_front_cart', 'Le produit a correctement été ajouté de votre panier');
        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * @Route("/r/{product_name}-{product_id}", name="odysseus_front_cart_remove", requirements={"product_name"=".+", "product_id"="\d+"})
     */
    public function removeProductAction(Request $request, $product_name, $product_id){
        $product = $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel')->find($product_id);
        if(!$product){
            throw $this->createNotFoundException();
        }
        
        $user=$this->getUser();
        $cart = $user->getActiveCart();
        
        $cart->removeModel($product);
        $cart->setModifiedAt(new \DateTime());
        $this->persistCart($cart);
        
        $this->addFlashBag('odysseus_front_cart', 'Le produit a correctement été supprimé de votre panier');
        return $this->redirect($request->headers->get('referer'));
    }
    
    private function persistCart($cart) {
        $this->getManager()->persist($cart);
        $this->getManager()->flush();
    }
    
    private function getManager(){
        return $this->getDoctrine()->getManager();
    }
    
    private function getRepository($model){
        return $this->getDoctrine()->getRepository("OdysseusAdminBundle:$model");
    }
    
    private function getSession(){
        return $this->get('session');
    }
    
    private function getFlashBag(){
        return $this->getSession()->getFlashBag();
    }
    
    private function addFlashBag($name, $value){
        return $this->getFlashBag()->add($name, $value);
    }
}
