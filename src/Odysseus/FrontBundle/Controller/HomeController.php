<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    public function contactAction()
    {
        return $this->render('OdysseusFrontBundle:Home:contact.html.twig');
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
     * @Route("/recherche", name="odysseus_user_homesearch")
     * @Route("/recherche/{page}", name="odysseus_user_homesearch_page", requirements={"page"="\d+"})
     * @Method({"GET"})
     */
    public function SearchAction(Request $request, $page = 1)
    {
        if(!$request->query->has('q'))
            throw $this->createNotFoundException();
        $q = $request->query->get('q');
        if(strlen($q)<2)
            throw $this->createNotFoundException();
        
        if($page < 1)
            $page = 1;
        
        $session = $this->get('session');
        $npp = ($session->has('category_npp') ? $session->get('category_npp') : 15 );
        $list_view = ($session->has('category_list_view') ? $session->get('category_list_view') : 'grid' );
        $sort = ($session->has('category_sort') ? $session->get('category_sort') : 'pert' );

        if ($request->query->has('npp')) {
            $npp = $request->query->get('npp');

            if (!is_numeric($npp) || !in_array($npp, array(5, 10, 20))) {
                $npp = 5;
            }

            $this->get('session')->set('category_npp', $npp);
        }

        if ($request->query->has('list_view')) {
            $list_view = $request->query->get('list_view');
            if (!in_array($list_view, array('grid', 'list'))) {
                $list_view = 'grid';
            }

            $this->get('session')->set('category_list_view', $list_view);
        }

        if ($request->query->has('sort')) {
            $sort = $request->query->get('sort');
            if (!in_array($sort, array('name-asc', 'name-desc', 'price-asc', 'price-desc', 'date-asc', 'date-desc'))) {
                $sort = 'name-asc';
            }

            $this->get('session')->set('category_sort', $sort);
        }
        
        $qdata = explode('+', preg_replace('#\s+#', '+', $q));
        $qdata[] = $q;
        
        return $this->render('OdysseusFrontBundle:Home:search.html.twig', array(
            'products' => $this->findProducts($qdata, $page, $npp, $sort),
            'listView' => $list_view,
            'npp' => (int) $npp,
            'sort' => $sort,
            'page' => $page,
            'q' => $q
        ));  
    }
    
    public function findProducts($q, $page = -1, $npp = -1, $sort = NULL) {

        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:Article')->createQueryBuilder('a');
        $builder->select('a')
                ->leftJoin('OdysseusAdminBundle:ArticleModel', 'am', 'WITH', 'am.article=a.id')
                ->leftJoin('OdysseusAdminBundle:ArticleModelStatus', 'ams', 'WITH', 'am.status=ams.id');
        
        $tot = array();
        foreach($q as $k=>$v){
            $builder->setParameter(":qv$k", "$v");
            $tot[] = "(2*(LENGTH(a.name)-LENGTH(REPLACE(LOWER(a.name), LOWER(:qv$k), '')))/LENGTH(:qv$k))";
            $tot[] = "(1.5*(LENGTH(a.brand)-LENGTH(REPLACE(LOWER(a.brand), LOWER(:qv$k), '')))/LENGTH(:qv$k))";
            $tot[] = "(0.5*(LENGTH(a.description)-LENGTH(REPLACE(LOWER(a.description), LOWER(:qv$k), '')))/LENGTH(:qv$k))";
        }
        $tot = implode('+', $tot);
        
        $builder->select('COUNT(am)');
        $builder->where("($tot) > 0");
        $count = $builder->getQuery()->getSingleScalarResult();
        
        $builder->select('ams', 'COUNT(a) total');
        $builder->groupBy('ams.id');
        $builder->where("($tot) > 0");
        $status = $builder->getQuery()->getResult();
        
        $builder->select('a', 'COUNT(a) total');
        $builder->orderBy('a.brand', 'ASC');
        $builder->groupBy('a.brand');
        $builder->where("($tot) > 0");
        $brands = $builder->getQuery()->getResult();
        
        $maxPage = 1;

        if ($page > 0 && $npp > 0) {
            $builder->setFirstResult(($page - 1) * $npp);
            $maxPage = ((int) ($count / $npp)) + (($count % $npp) != 0 ? 1 : 0);
        }

        if ($npp > 0) {
            $builder->setMaxResults($npp);
        }
        
        
        switch ($sort) {
            case 'name-asc':
                $builder->orderBy('a.name', 'ASC');
                break;
            case 'name-desc':
                $builder->orderBy('a.name', 'DESC');
                break;
            case 'price-asc':
                $builder->orderBy('am.price', 'ASC');
                break;

            case 'price-desc':
                $builder->orderBy('am.price', 'DESC');
                break;

            case 'date-asc':
                $builder->orderBy('am.createdAt', 'ASC');
                break;

            case 'date-desc':
                $builder->orderBy('am.createdAt', 'DESC');
                break;
            
            default:
                $builder->orderBy('nb', 'DESC');
        }
        
        $builder->select('a articles', "($tot) nb");
        $builder->where("($tot) > 0");
        $builder->groupBy('a.id');
        
        
        $rows = $builder->getQuery()->getResult();
        $pagination = (Object) array(
                    'min' => (($page > 0 && ($page - 3) > 0) ? $page - 3 : 1),
                    'max' => (($page + 3) > $maxPage ? $maxPage : ($page + 3)),
                    'cur' => ($page > 0 ? $page : $page),
                    'count' => $maxPage
        );
        return (Object) array(
                'rows' => $rows,
                'count' => $count,
                'pagination' => $pagination,
                'statuses' => $status,
                'brands' => $brands
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
                    ), 4);
    }
}
