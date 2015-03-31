<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use Odysseus\AdminBundle\Entity\UserInfos;
use Odysseus\AdminBundle\Entity\User;
use Odysseus\AdminBundle\Form\UserType;

/**
 * @Route("/admin/user", name="odysseus_admin_user_root")
 */
class UserController extends Controller {

    /**
     * @Route("/", name="odysseus_admin_user", defaults={"page"="1"})
     * @Route("/page/{page}", name="odysseus_admin_user_page", requirements={"page"="\d+"})
     */
    public function indexAction($page = 1) {
        $ppp = 10;
        $users = $this->getUsers($page, $ppp);
        $count = $this->getUsersCount();
        return $this->render('OdysseusAdminBundle:User:index.html.twig', array(
                    'users' => $users,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count)
        ));
    }
    
    /**
     * @Route("/create", name="odysseus_admin_user_create")
     */
    public function createAction(Request $request) {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        
        $defaultInfos = new UserInfos();
        $defaultInfos->setIsDefault(true);
        $defaultInfos->setIsBuilling(false);
        $defaultInfos->setCreatedAt(new \DateTime());
        $defaultInfos->setModifiedAt(new \DateTime());
        $defaultInfos->setUser($user);
        
        $buillingInfos = new UserInfos();
        $buillingInfos->setIsDefault(false);
        $buillingInfos->setIsBuilling(true);
        $buillingInfos->setCreatedAt(new \DateTime());
        $buillingInfos->setModifiedAt(new \DateTime());
        $buillingInfos->setUser($user);
        
        $user->addInfo($defaultInfos);
        $user->addInfo($buillingInfos);
        
        /*
        $defaultInfos->setUser($user);
        $buillingInfos->setUser($user);
        */
        
        $form = $this->createForm(new UserType(), $user);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('odysseus_admin_user_form', 'Utilisateur créé avec succès');
            
                $this->redirect($this->generateURL('odysseus_admin_user_update', array(
                    'id' => $user->getId()
                )));
            }
        }
        return $this->render('OdysseusAdminBundle:User:create.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/update/{id}", name="odysseus_admin_user_update", requirements={"id"="\d+"})
     */
    public function updateAction(Request $request, $id) {
        $user = $this->getUserEntity($id);
        if (!$user) {
            return $this->createNotFoundException('L\'user n\'existe pas');
        }
        
        $userManager = $this->container->get('fos_user.user_manager');
        
        if($user->getDefaultInfos() == NULL){
            $defaultInfos = new UserInfos();
            $defaultInfos->setIsDefault(true);
            $defaultInfos->setIsBuilling(false);
            $defaultInfos->setCreatedAt(new \DateTime());
            $defaultInfos->setModifiedAt(new \DateTime());
            $defaultInfos->setUser($user);
            $user->addInfo($defaultInfos);
        }
        
        if(count($user->getInfos()) < 2){
            $buillingInfos = new UserInfos();
            $buillingInfos->setIsDefault(false);
            $buillingInfos->setIsBuilling(true);
            $buillingInfos->setCreatedAt(new \DateTime());
            $buillingInfos->setModifiedAt(new \DateTime());
            $buillingInfos->setUser($user);
            $user->addInfo($buillingInfos);
        }
        
        $form = $this->createForm(new UserType(), $user);

        if($request->getMethod() == 'POST'){
            $form->handleRequest($request);
            
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('odysseus_admin_user_form', 'Utilisateur modifié avec succès');
            
                $this->redirect($this->generateURL('odysseus_admin_user_update', array(
                    'id' => $user->getId()
                )));
            }
        }

        return $this->render('OdysseusAdminBundle:User:update.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="odysseus_admin_user_delete", requirements={"id"="\d+"})
     */
    public function deleteAction($id) {
        $user = $this->getUserEntity($id);
        if (!$user) {
            return $this->createNotFoundException('L\'user n\'existe pas');
        }

        $this->removeUser($user);
        $this->get('session')->getFlashBag()->add('odysseus_admin_user', 'Utilisateur supprimé avec succès');
        return $this->redirect($this->generateURL('odysseus_admin_user'));
    }
    
    /**
     * @Route("/disable/{id}", name="odysseus_admin_user_disable", requirements={"id"="\d+"})
     */
    public function disableAction(Request $request, $id) {
        $user = $this->getUserEntity($id);
        if (!$user) {
            return $this->createNotFoundException('L\'user n\'existe pas');
        }

        $user->setEnabled(false);
        
        $em = $this->container->get('fos_user.user_manager');
        $em->updateUser($user);
        
        $this->get('session')->getFlashBag()->add('odysseus_admin_user', 'Utilisateur désactivé avec succès');
        return $this->redirect($request->headers->get('referer'));
    }
    
    /**
     * @Route("/enable/{id}", name="odysseus_admin_user_enable", requirements={"id"="\d+"})
     */
    public function enableAction(Request $request, $id) {
        $user = $this->getUserEntity($id);
        if (!$user) {
            return $this->createNotFoundException('L\'user n\'existe pas');
        }

        $user->setEnabled(true);
        
        $em = $this->container->get('fos_user.user_manager');
        $em->updateUser($user);
        
        $this->get('session')->getFlashBag()->add('odysseus_admin_user', 'Utilisateur activé avec succès');
        return $this->redirect($request->headers->get('referer'));
    }
    
    private function getUsers($page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:User')->findBy(array(), null, $count, $page * $count);
    }

    private function getUsersCount() {
        return $this->getDoctrine()->getEntityManager()->createQueryBuilder()->select('COUNT(a)')->from('OdysseusAdminBundle:User', 'a')->getQuery()->getSingleScalarResult();
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

    private function getUserEntity($id) {
        return $this->getDoctrine()->getRepository('OdysseusAdminBundle:User')->find($id);
    }

    private function removeUser($id_or_user) {
        $user = $id_or_user;
        if (is_numeric($id_or_user))
            $user = $this->getUserEntity($id_or_user);

        if (!$user)
            return FALSE;

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        try {
            $em->flush();
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

}
