<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/{category}", requirements={"category"="[^/]+"})
 */
class ArticleController extends Controller {

    /**
     * @Route("/{name}-{id}", name="odysseus_front_product_details", requirements={"name"=".+", "id"="\d+"})
     * @Route("/{name}-{id}/page/{page}", name="odysseus_front_product_details_page", requirements={"name"=".+", "id"="\d+", "page"="\d+"})
     */
    public function indexAction($name, $id, $page = 1) {

        $ppp = 10;
        $articleDetail = $this->getArticleDetail($id, $page, $ppp);
        $count = $this->getModelsCount($articleDetail->getId());
        return $this->render('OdysseusFrontBundle:Article:details.html.twig', array(
                    'article' => $articleDetail,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count),
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

    private function getModelsCount($idArticle){
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
