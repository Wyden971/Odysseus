<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
/**
 * @Route("/{category}", requirements={"category"="[^/]+"})
 */
class ArticleController extends Controller {

    /**
     * @Route("/{name}-{id}", name="odysseus_front_product_details", requirements={"name"=".+", "id"="\d+"})
     * @Route("/{name}-{id}/{page}", name="odysseus_front_product_details_page", requirements={"name"=".+", "id"="\d+", "page"="\d+"})
     */
    public function indexAction(Request $request,$name, $id, $page = 1) {
        $session = $this->get('session');
        if ($page < 1)
            $page = 1;

        $npp = ($session->has('models_npp') ? $session->get('models_npp') : 10 );
        $list_view = ($session->has('models_list_view') ? $session->get('models_list_view') : 'grid' );
        $sort = ($session->has('models_sort') ? $session->get('models_sort') : 'name-asc' );

        if ($request->query->has('npp')) {
            $npp = $request->query->get('npp');

            if (!is_numeric($npp) || !in_array($npp, array(5, 10, 20))) {
                $npp = 5;
            }

            $this->get('session')->set('models_npp', $npp);
        }

        if ($request->query->has('list_view')) {
            $list_view = $request->query->get('list_view');
            if (!in_array($list_view, array('grid', 'list'))) {
                $list_view = 'grid';
            }

            $this->get('session')->set('models_list_view', $list_view);
        }

        if ($request->query->has('sort')) {
            $sort = $request->query->get('sort');
            if (!in_array($sort, array('name-asc', 'name-desc', 'price-asc', 'price-desc', 'date-asc', 'date-desc'))) {
                $sort = 'name-asc';
            }

            $this->get('session')->set('models_sort', $sort);
        }
        
        $articleDetail = $this->getArticleDetail($id, $page, $npp);
        $count = $this->getModelsCount($articleDetail->getId());
        return $this->render('OdysseusFrontBundle:Article:details.html.twig', array(
                    'article' => $articleDetail,
                    'count' => $count,
                    'npp' => (int) $npp,
                    'listView' => $list_view,
                    'sort' => $sort,
                    'page' => $page,
                    'pagination' => $this->getPagination($page, $npp, $count),
        ));
    }

    private function getArticleDetail($id, $page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getRepository()
                        ->createQueryBuilder('a')
                        ->select('a')
                        ->where('a.id = ' . $id)
                        ->setFirstResult($page * $count)
                        ->setMaxResults($count)->getQuery()->getSingleResult();
    }

    private function getModelsCount($idArticle) {
        return $this->getDoctrine()
                        ->getRepository('OdysseusAdminBundle:ArticleModel')
                        ->createQueryBuilder('am')
                        ->select('COUNT(am)')
                        ->where('am.article = ' . $idArticle)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    public function getRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:Article');
    }

    public function getArticleModelRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel');
    }

    public function getCategoryRepository() {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleCategory');
    }

    public function getCategory($id) {
        return $this->getCategoryRepository()->find($id);
    }

    public function getCategoryByName($name) {
        return $this->getCategoryRepository()->findOneByUrl($name);
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

}
