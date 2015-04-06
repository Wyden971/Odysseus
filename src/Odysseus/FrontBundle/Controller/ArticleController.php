<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Odysseus\FrontBundle\Form\ArticleModelType;
use Odysseus\AdminBundle\Entity\ArticleModel;
use Odysseus\AdminBundle\Form\ArticleType;
use Odysseus\AdminBundle\Entity\Image;
use Symfony\Component\Form\FormError;

/**
 * @Route("/{category}/{name}-{id}", requirements={"category"="[^/]+","name"=".+","id"="\d+"})
 */
class ArticleController extends Controller {

    /**
     * @Route("/", name="odysseus_front_product_details")
     */
    public function indexAction($category, $name, $id) {
        
        $product = $this->getArticleModelRepository()->find($id);
                
        if(!$product){
            throw $this->createNotFoundException();
        }
        
        $builder = $this->getArticleModelRepository()->createQueryBuilder('am');
        $builder->select('am')
                ->where('am.article=:aid')
                ->andWhere('am.id!=:mid')
                ->setParameter(':mid', $product->getId())
                ->setParameter(':aid', $product->getArticle()->getId())
                ->setMaxResults(4)
                ;
        $more_models = $builder->getQuery()->getResult();
        
        return $this->render('OdysseusFrontBundle:Article:details.html.twig', array(
            'product' => $product,
            'more_models' => $more_models,
            'category_name' => $product->getArticle()->getCategory()->getUrl()
        ));
    }
    
    /**
     * @Route("/vendu-par-d-autres-vendeurs", name="odysseus_front_product_more")
     * @Route("/vendu-par-d-autres-vendeurs/{page}", name="odysseus_front_product_more_page", requirements={"page":"\d+"})
     */
    public function moreAction(Request $request, $category, $name, $id, $page=1) {
        
        if($page<1)
            $page = 1;
        $product = $this->getArticleModelRepository()->find($id);
                
        if(!$product){
            throw $this->createNotFoundException();
        }
        
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

        return $this->render('OdysseusFrontBundle:Article:index.html.twig', array(
            'product' => $product,
            'products' => $this->findProducts($product, $page, $npp, $sort),
            'listView' => $list_view,
            'npp' => (int) $npp,
            'sort' => $sort,
            'page' => $page,
            'category_name' => $product->getArticle()->getCategory()->getUrl()
            
        ));
    }
    
    /**
     * @Route("/ajouter-un-exemplaire", name="odysseus_front_product_create")
     */
    public function addAction(Request $request, $category, $name, $id) {
        
        $product = $this->getArticleModelRepository()->find($id);
                
        if(!$product){
            throw $this->createNotFoundException();
        }
        
        $model = new ArticleModel();
        $model->addImage(new Image());
        $model->addImage(new Image());
        $model->addImage(new Image());
        $model->setArticle(clone $product->getArticle());
        $model->setCreatedAt(new \DateTime());
        $model->setUser($this->getUser());
        
        $form = $this->createForm(new ArticleModelType(), $model, array(
            'method' => 'POST',
            'attr' => array(
                'class' => 'form-horizontal'
            )
        ));
        $form->add('article', new ArticleType());
        $form->get('article')->remove('models');
       
        
        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            
            $imageData = $form->get('image')->getData();
            $image1 = $imageData[0];
            
            if($image1->file == NULL){
                $form->get('image')->addError(new FormError('Vous devez obligatoirement uploader au moins une photo'));
            }else{
                $image2 = $imageData[1];
                $image3 = $imageData[2];
                
                if($image2->file == NULL){
                    $model->removeImage($image2);
                }
                
                if($image3->file == NULL){
                    $model->removeImage($image3);
                }
            }
            $model->setArticle($product->getArticle());  
  
            if($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $em->persist($model);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('odysseus_front_product_details', 'Article ajouté avec succès');
                return $this->redirect($this->generateURL('odysseus_front_product_details', array(
                    'category' => $category,
                    'name' => $name,
                    'id' => $model->getId()
                )));
            }
        }
        return $this->render('OdysseusFrontBundle:Article:form.html.twig', array(
            'form' => $form->createView(),
            'model' => $model,
            'category_name' => $product->getArticle()->getCategory()->getUrl()
        ));
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
    
    public function findProducts($product, $page = -1, $npp = -1, $sort = NULL) {

        $builder = $this->getDoctrine()->getRepository('OdysseusAdminBundle:ArticleModel')->createQueryBuilder('am');
        $builder->where('am.article=:aid')
                ->andWhere('am.id!=:mid')
                ->setParameter(':mid', $product->getId())
                ->setParameter(':aid', $product->getArticle()->getId())
                ;
        
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
        }
        
        
        
        $builder->select('COUNT(am)');
        $count = $builder->getQuery()->getSingleScalarResult();
        
        $maxPage = 1;

        if ($page > 0 && $npp > 0) {
            $builder->setFirstResult(($page - 1) * $npp);
            $maxPage = ((int) ($count / $npp)) + (($count % $npp) != 0 ? 1 : 0);
        }

        if ($npp > 0) {
            $builder->setMaxResults($npp);
        }
        
        $builder->select('am');
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
        );
    }
}