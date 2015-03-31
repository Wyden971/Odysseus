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
            new \Twig_SimpleFilter('truncText', array($this, 'truncText')),
            new \Twig_SimpleFilter('forUri', array($this, 'forUri')),
        );
    }
    
    public function truncText($text, $size = 50){
        return substr($text, 0, $size).(strlen($text) > $size ? '...' : '');
    }
    public function getCategories(){
        return $this->em->getRepository('OdysseusAdminBundle:ArticleCategory')->findBy(array(), array('order' => 'ASC'));
    }
    
    public function forUri($text){
        $text = preg_replace('#([àâä])#', 'a', $text);
        $text = preg_replace('#([éèêë])#', 'e', $text);
        $text = preg_replace('#([ìïî])#', 'i', $text);
        $text = preg_replace('#([ôöò])#', 'o', $text);
        $text = preg_replace('#([ùüû])#', 'u', $text);
        $text = preg_replace('#([ÿ])#', 'y', $text);
        return strtolower(preg_replace('#([^a-zA-Z0-9]+)#', '-', $text));
    }
    
    
    public function getName()
    {
        return 'odysseus_extension';
    }
}