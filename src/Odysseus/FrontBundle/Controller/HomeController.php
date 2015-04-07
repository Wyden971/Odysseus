<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */ 
class HomeController extends Controller
{
    /**
     * @Route("/", name="odysseus_front_homepage")
     * @Route("/accueil")
     * @Route("/home")
     */
    public function indexAction()
    {   
        return $this->render('OdysseusFrontBundle:Home:index.html.twig', array(
            'lastArticles' => $this->getLastArticles()
        ));
    }
	
    /**
     * @Route("/contact", name="odysseus_front_contact")
     * @Route("/nous-contacter")
     */
    public function contactAction(\Symfony\Component\HttpFoundation\Request $request)
    {
        $user = $this->getUser();
        $valid = false;
        if ($request->getMethod() == 'POST') {
            $message = $this->get('request')->request->get('message');
            $this->sendEmailSupport($user, $message);
            $valid = true;
        }
        return $this->render('OdysseusFrontBundle:Home:contact.html.twig',
                array(
                    'user' => $user,
                    'valid' => $valid
                    ));
    }
	
    /**
     * @Route("/faq", name="odysseus_front_faq")
     * @Route("/foire-aux-questions")
     */
    public function faqAction()
    {
        return $this->render('OdysseusFrontBundle:Home:faq.html.twig');
    }
	
    /**
     * @Route("/mentions-legales", name="odysseus_front_policy")
     * @Route("/policy")
     */
    public function policyAction()
    {
        return $this->render('OdysseusFrontBundle:Home:policy.html.twig');
    }
    
    /**
     * @Route("/search", name="odysseus_user_homesearch")
     */
    public function SearchAction()
    {
        // A voir pour une recherche avec des critères avancés, ou recherche selon la page ou l'on se trouve 
        return array(
                // ...
            );    
    }
    
    /**
     * @Route("/MentionsLegales", name="odysseus_user_mentionslegales")
     */
    public function MentionsLegalesAction()
    {
        return array(
                // ...
            );    
    }

    /**
     * @Route("/Newsletter", name="odysseus_user_homenewsletter")
     */
    public function NewsletterAction()
    {
        return array(
                // ...
            );    
    }
    
    public function getLastArticles(){
        return $this->getDoctrine()
                ->getRepository('OdysseusAdminBundle:ArticleModel')
                ->findBy(array(), array(
                    'createdAt' => 'DESC'
                    ), 3);
    }
    private function sendEmailSupport($user, $message) {
        $twig = $this->get('twig');
        
        $template = $twig->loadTemplate('Mail/contact.twig');
        $parameters = array(
            'user' => $user,
            'message' => $message,
        );
        $subject = $template->renderBlock('subject', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($user->getEmail())
                ->setTo('yoann.pierre93@gmail.com')
                ->setBody($bodyText, 'text/plain')
                ->addPart($bodyHtml, 'text/html')
        ;
        $this->get('mailer')->send($message);
    }
}
