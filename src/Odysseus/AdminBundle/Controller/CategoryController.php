<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityRepository;
use Odysseus\AdminBundle\Entity\ArticleCategory;
use Odysseus\AdminBundle\Form\ArticleCategoryType;

/**
 * @Route("/admin/category")
 */
class CategoryController extends Controller {

    /**
     * @Route("/", name="odysseus_admin_category", defaults={"page"="1"})
     * @Route("/page/{page}", name="odysseus_admin_category_page", requirements={"page"="\d+"})
     */
    public function indexAction($page = 1) {
        $ppp = 10;
        $categories = $this->getCategorys($page, $ppp);
        $count = $this->getCategorysCount();
        return $this->render('OdysseusAdminBundle:Category:index.html.twig', array(
                    'categories' => $categories,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count)
        ));
    }

    /**
     * @Route("/update/{id}", name="odysseus_admin_category_update", requirements={"id"="\d+"})
     */
    public function updateAction($id, Request $request) {
        $category = $this->getCategoryEntity($id);
        if (!$category) {
            return $this->createNotFoundException('La cétégorie n\'existe pas');
        }

        $form = $this->createForm(new ArticleCategoryType(), $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('odysseus_admin_category_form', 'Catégorie sauvegardée avec succès');
        
            return $this->redirect($this->generateURL('odysseus_admin_category_update', array('id' => $id)));
        }

        return $this->render('OdysseusAdminBundle:Category:update.html.twig', array(
                    'category' => $category,
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/create", name="odysseus_admin_category_create")
     */
    public function createAction(Request $request) {
        $category = new ArticleCategory();

        $form = $this->createForm(new ArticleCategoryType(), $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('odysseus_admin_category_form', 'Catégorie crée avec succès');
        
            return $this->redirect($this->generateURL('odysseus_admin_category_create'));
        }

        return $this->render('OdysseusAdminBundle:Category:create.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="odysseus_admin_category_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id) {
        $category = $this->getCategoryEntity($id);
        if (!$category) {
            return $this->createNotFoundException('La catégorie n\'existe pas');
        }

        $this->removeCategory($category);
        
        $this->get('session')->getFlashBag()->add('odysseus_admin_category', 'Catégorie supprimé avec succès');
        
        return $this->redirect($this->generateURL('odysseus_admin_category'));
    }

    private function getCategorys($page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleCategory')->findBy(array(), null, $count, $page * $count);
    }

    private function getCategorysCount() {
        return $this->getDoctrine()->getEntityManager()->createQueryBuilder()->select('COUNT(a)')->from('OdysseusAdminBundle:ArticleCategory', 'a')->getQuery()->getSingleScalarResult();
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

    private function getCategoryEntity($id) {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleCategory')->find($id);
    }

    private function removeCategory($id_or_category) {
        $category = $id_or_category;
        if (is_numeric($id_or_category))
            $category = $this->getCategoryEntity($id_or_category);

        if (!$category)
            return FALSE;

        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        try {
            $em->flush();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

}
