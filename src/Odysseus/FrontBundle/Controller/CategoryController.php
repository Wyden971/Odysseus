<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @Route("/c/{category_name}", requirements={"category_name"="[a-zA-Z0-9_-]+"})
 */
class CategoryController extends Controller {

    /**
     * @Route("/", defaults={"page"="1"}, requirements={"page"="\d+"}, name="odysseus_category_index")
     * @Route("/{page}", defaults={"page"="1"}, requirements={"page"="\d+"}, name="odysseus_category_index_page")
     */
    public function indexAction(Request $request, $category_name, $page) {
        $category = $this->getCategoryByName($category_name);
        $session = $this->get('session');

        if (!$category)
            throw $this->createNotFoundException();

        if ($page < 1)
            $page = 1;

        $npp = ($session->has('category_npp') ? $session->get('category_npp') : 5 );
        $list_view = ($session->has('category_list_view') ? $session->get('category_list_view') : 'grid' );
        $sort = ($session->has('category_sort') ? $session->get('category_sort') : 'name-asc' );

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

        
        return $this->render('OdysseusFrontBundle:Category:index.html.twig', array(
                    'category' => $category,
                    'products' => $this->findProductsByCategory($category, $page, $npp, $sort),
                    'listView' => $list_view,
                    'npp' => (int) $npp,
                    'sort' => $sort,
                    'page' => $page,
                    'category_name' => $category_name
        ));
    }

    public function getRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleCategory');
    }

    public function getCategory($id) {
        return $this->getRepository()->find($id);
    }

    public function getCategoryByName($name) {
        return $this->getRepository()->findOneByUrl($name);
    }

    public function findProductsByCategory(&$category, $page = -1, $npp = -1, $sort = NULL) {

        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:Article')->createQueryBuilder('a');
        $builder->select('a')
                ->leftJoin('OdysseusAdminBundle:ArticleModel', 'am', 'WITH', 'am.article=a.id')
                ->leftJoin('OdysseusAdminBundle:ArticleModelStatus', 'ams', 'WITH', 'am.status=ams.id')
                ->where('a.category=:category')
                ->setParameter(':category', $category->getId())
        ;

        $builder->select('COUNT(am)');
        $count = $builder->getQuery()->getSingleScalarResult();

        $builder->select('ams', 'COUNT(a) total');
        $builder->groupBy('ams.id');
        $status = $builder->getQuery()->getResult();

        $builder->select('a', 'COUNT(a) total');
        $builder->orderBy('a.brand', 'ASC');
        $builder->groupBy('a.brand');
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
                $builder->orderBy('a.id', 'ASC');
        }

        $builder->select('a');
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

}
