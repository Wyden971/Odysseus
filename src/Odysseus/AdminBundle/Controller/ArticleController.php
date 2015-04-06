<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormError;

use Odysseus\AdminBundle\Entity\ArticleModel;
use Odysseus\AdminBundle\Entity\Article;
use Odysseus\AdminBundle\Form\ArticleType;
use Odysseus\AdminBundle\Form\ArticleModelType;
use Odysseus\AdminBundle\Entity\Image;

/**
 * @Route("/admin/article", name="odysseus_admin_article_root")
 */
class ArticleController extends Controller {

    /**
     * @Route("/", name="odysseus_admin_article", defaults={"page"="1"})
     * @Route("/page/{page}", name="odysseus_admin_article_page", requirements={"page"="\d+"})
     */
    public function indexAction($page = 1) {
        $ppp = 10;
        $articles = $this->getArticles($page, $ppp);
        $count = $this->getArticlesCount();
        return $this->render('OdysseusAdminBundle:Article:index.html.twig', array(
                    'articles' => $articles,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count)
        ));
    }
    
    
    /**
     * @Route("/update/{id}", name="odysseus_admin_article_update", requirements={"id"="\d+"})
     */
    public function updateAction(Request $request, $id) {
        $article = $this->getArticle($id);
        if (!$article) {
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $form = $this->createForm(new ArticleModelType(), $article);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            $images = $model->getImage();
            if(empty($images) || $images[0]->file === null){
                $form->get('model')->get('image')->get(0)->addError(new FormError('Image obligatoire'));
            }
           
            if($form->isValid()){
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('odysseus_admin_article_update', 'Votre article a été enregistré correctement');
            
                $this->redirect($this->generateUrl('odysseus_admin_article_update', array(
                    'id' => $article->getId()
                )));
            }
        }
        
        return $this->render('OdysseusAdminBundle:Article:update.html.twig', array(
            'article' => $article,
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/validate/{id}", name="odysseus_admin_article_validate", requirements={"id"="\d+"})
     */
    public function validateAction(Request $request, $id) {
        $model = $this->getArticle($id);
        if (!$model) {
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }
        $article = $model->getArticle();
        
        if($article->getValidatedAt() == NULL){
            $article->setValidatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
        }
        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/delete/{id}", name="odysseus_admin_article_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id) {
        $model = $this->getArticle($id);
        if (!$model) {
            return $this->createNotFoundException('L\'article n\'existe pas');
        }
        
        $this->removeArticle($model);
        
        $this->get('session')->getFlashBag()->add('odysseus_admin_article_delete', 'Votre article a été supprimé');

        return $this->redirect($this->generateURL('odysseus_admin_article'));
    }

    private function getArticles($page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel')->findBy(array(), null, $count, $page * $count);
    }

    private function getArticlesCount() {
        return $this->getDoctrine()->getEntityManager()->createQueryBuilder()->select('COUNT(a)')->from('OdysseusAdminBundle:ArticleModel', 'a')->getQuery()->getSingleScalarResult();
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

    private function getArticle($id) {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel')->find($id);
    }

    private function removeArticle($id_or_article) {
        $model = $id_or_article;
        if (is_numeric($id_or_article))
            $model = $this->getArticle($id_or_article);

        if (!$model)
            return FALSE;
        
        $article = clone $model->getArticle();
        
        $article->remove($model);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        
        $models = $article->getModels();
        if(empty($models))
            $em->remove($article);
            
        try {
            $em->flush();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

}
