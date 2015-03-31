<?php

namespace Odysseus\FrontBundle\Twig;

use Doctrine\ORM\EntityManager;

class OdysseusExtension extends \Twig_Extension
{
    var $em;
    
    public function __construct(EntityManager $entityManager){
        
        $this->em = $entityManager;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getCategories', array($this, 'getCategories')),
        );
    }
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('truncText', array($this, 'truncText'))
        );
    }
    
    public function truncText($text, $size = 50){
        return substr($text, 0, $size).(strlen($text) > $size ? '...' : '');
    }
    public function getCategories(){
        return $this->em->getRepository('OdysseusAdminBundle:ArticleCategory')->findBy(array(), array('order' => 'ASC'));
    }

    public function getName()
    {
        return 'odysseus_extension';
    }
}