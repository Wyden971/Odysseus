<?php

namespace Odysseus\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Odysseus\FrontBundle\Form\UserType;
use Odysseus\FrontBundle\Form\UserInfosType;
use Odysseus\AdminBundle\Entity\UserInfos;

/**
 * @Route("/")
 */
class UserController extends Controller {
    /*
     * @Route("/connexion", name="odysseus_front_user_login")
     * @Route("/login")

      public function loginAction()
      {
      return $this->render('OdysseusFrontBundle:User:login.html.twig');
      }
     */

    /**
     * @Route("/inscription", name="odysseus_front_user_signup")
     * @Route("/signup")
     * @Route("/register")
     */
    public function registerAction(Request $request) {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $shippingInfos = new UserInfos();
        $buillingInfos = new UserInfos();

        $shippingInfos->setCreatedAt(new \DateTime());
        $shippingInfos->setModifiedAt(new \DateTime());

        $buillingInfos->setCreatedAt(new \DateTime());
        $buillingInfos->setModifiedAt(new \DateTime());

        $shippingInfos->setUser($user);
        $buillingInfos->setUser($user);

        $shippingInfos->setIsDefault(true);
        $buillingInfos->setIsBuilling(true);

        $user->addInfo($shippingInfos);
        $user->addInfo($buillingInfos);

        $form = $this->createForm(new UserType(), $user);

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($userManager->findUserByEmail($user->getEmail())) {
                $form->get('email')->addError(new FormError('L\'email existe déjà'));
            }


            if ($form->isValid()) {

                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
                $user->setEnabled(false);
                $user->setUserName($user->getEmail());
                $user->addRole('ROLE_USER');
                $this->sendEmailConfirmation($user);
                $userManager->updateUser($user);


                return $this->redirect($this->generateUrl('odysseus_front_user_validated', array(
                                    'token' => $user->getConfirmationToken()
                )));
            }
        }

        return $this->render('OdysseusFrontBundle:User:register.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/confirmation/{token}", name="odysseus_front_user_validated")
     */
    public function registerValidatedAction(Request $request, $token) {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);
        if (!$user)
            throw $this->createNotFoundException();


        return $this->render('OdysseusFrontBundle:User:validate.html.twig', array(
                    'token' => $token,
                    'user' => $user
        ));
    }

    /**
     * @Route("/validation/{token}", name="odysseus_front_user_validation")
     * @Route("/validate/{token}")
     */
    public function validateAction(Request $request, $token) {

        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->findUserByConfirmationToken($token);
        if (!$user)
            throw $this->createNotFoundException();

        $user->setConfirmationToken(NULL);
        $user->setEnabled(true);

        $userManager->updateUser($user);


        $csrfToken = $this->has('form.csrf_provider') ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate') : null;

        return $this->render('OdysseusFrontBundle:User:validation.html.twig', array(
                    'csrf_token' => $csrfToken
        ));
    }

    /**
     * @Route("/mon-compte/mes-coordonnees", name="odysseus_front_user_my_infos")
     */
    public function myInfosAction(Request $request) {
        $user = $this->getUser();

        $form = $this->createForm(new UserType(), $user);
        $form->remove('birthdate');
        $form->remove('plainPassword');
        $form->remove('email');
        $form->remove('receiveSpecialOffers');
        $form->remove('receivePartnersSpecialOffers');
        $form->add('sameInfos', 'checkbox', array(
            'mapped' => false,
            'value' => 'yes',
            'required' => false
        ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $buillingInfos = $user->getBuillingInfos();
            $defaultInfos = $user->getDefaultInfos();

            $buillingInfos->setIsBuilling(true);
            $defaultInfos->setIsDefault(true);

            if ($form->get('sameInfos')->getData() != 'yes') {

                $buillingInfos->setCivility($defaultInfos->getCivility());
                $buillingInfos->setFirstName($defaultInfos->getFirstName());
                $buillingInfos->setLastName($defaultInfos->getLastName());
                $buillingInfos->setCompany($defaultInfos->getCompany());
                $buillingInfos->setAddress1($defaultInfos->getAddress1());
                $buillingInfos->setAddress2($defaultInfos->getAddress2());
                $buillingInfos->setZipCode($defaultInfos->getZipCode());
                $buillingInfos->setCity($defaultInfos->getCity());
                $buillingInfos->setCountry($defaultInfos->getCountry());
                $buillingInfos->setTelephone($defaultInfos->getTelephone());
                $buillingInfos->setMobilePhone($defaultInfos->getMobilePhone());
            }
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->get('session')->getFlashBag()->add('odysseus_front_user_my_infos', 'Informations enregistrées avec succès');

                return $this->redirect($this->generateUrl('odysseus_front_user_my_infos'));
            }
        }

        return $this->render('OdysseusFrontBundle:User:my_infos.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Mes coordonnées'),
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/mon-compte/mes-achats", name="odysseus_front_user_my_orders")
     * @Route("/mon-compte/mes-achats/page/{page}", name="odysseus_front_user_my_orders_page")
     */
    public function myOrdersAction($page = 1) {
        $user = $this->getUser();
        $ppp = 10;
        $orders = $this->getCommandes($user->getId(), $page, $ppp);
        $articlePerOrder = array();
        $totalPrice = array();
        $em = $this->getDoctrine()->getManager()->getRepository('OdysseusAdminBundle:OrderArticle');
        foreach ($orders as $order) {
            $tempOrderArticle = $em->findBy(array('order' => $order->getId()));
            $articlePerOrder[$order->getId()] = sizeof($tempOrderArticle) >= 1 ? sizeof($tempOrderArticle) : 0;
            $totalPrice[$order->getId()] = $this->getTotalPrice($tempOrderArticle);
        }
        $count = $this->getCommandesCount($user->getId());

        return $this->render('OdysseusFrontBundle:User:my_orders.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Vos achats'),
                    'orders' => $orders,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count),
                    'articlePerCommand' => $articlePerOrder,
                    'prices' => $totalPrice,
        ));
    }

    /**
     * @Route("/mon-compte/", name="odysseus_front_user_my_summary")
     */
    public function mySummaryAction() {
        // Informations de base
        $user = $this->getUser();
        $em = $this->getDoctrine()->getRepository('OdysseusAdminBundle:OrderArticle');
        $countVentes = $em->createQueryBuilder('oa')
                ->select('COUNT(oa)')
                ->join('oa.model', 'm')
                ->where('m.user = ' . $user->getId())
                ->getQuery()
                ->getSingleScalarResult();
        // derniers articles vendu/achetés
        $lastTransactArticle = $em->createQueryBuilder('oa')
                ->select('COUNT(oa)')
                ->join('oa.order', 'o')
                ->join('oa.model', 'm')
                #->where('o.user = ' . $user->getId())
                ->where('m.user = ' . $user->getId());
        // derniers articles ajoutés
        $lastAddedArticle = $this->getUserProducts($user->getId(), 1, 5);
        return $this->render('OdysseusFrontBundle:User:my_summary.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Récapitulatif'),
                    'countVentes' => $countVentes,
                    'lastTransactArticle' => $lastTransactArticle,
                    'lastAddedArticle' => $lastAddedArticle
        ));
    }

    /**
     * @Route("/mon-compte/mes-informations-personnelles", name="odysseus_front_user_my_personal_informations")
     */
    public function myPersonalInformationsAction(Request $request) {
        $infos = $this->getUser()->getDefaultInfos();

        $form = $this->createForm(new UserInfosType(), $infos);

        $form->add('user', new UserType());
        $form->get('user')->remove('email');
        $form->get('user')->remove('receiveSpecialOffers');
        $form->get('user')->remove('receivePartnersSpecialOffers');
        $form->get('user')->remove('plainPassword');
        $form->get('user')->remove('infos');
        $form->remove('company');
        $form->remove('address1');
        $form->remove('address2');
        $form->remove('zipCode');
        $form->remove('city');
        $form->remove('country');
        $form->remove('telephone');
        $form->remove('mobilePhone');


        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($infos);
                $em->flush();

                $this->get('session')->getFlashBag()->add('odysseus_front_user_my_personal_informations', 'Informations enregistrées avec succès');
                return $this->redirect($this->generateUrl('odysseus_front_user_my_personal_informations'));
            }
        }

        return $this->render('OdysseusFrontBundle:User:my_personal_informations.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Informations personnelles'),
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/mon-compte/mes-achats/{id}", name="odysseus_front_user_my_order_details", requirements={"id"="\d+"})
     */
    public function myOrderDetailsAction($id) {

        $orderDetail = $this->getDoctrine()
                ->getManager()
                ->getRepository('OdysseusAdminBundle:OrderArticle')
                ->createQueryBuilder('oa')
                ->select('oa')
                ->join('oa.order', 'o')
                ->where('o.id = ' . $id)
                ->getQuery()
                ->getResult();

        return $this->render('OdysseusFrontBundle:User:my_order_details.html.twig', array(
                    'articles' => $orderDetail,
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Détails de la commandes'),
        ));
    }

    /**
     * @Route("/mon-compte/modifier-mon-mot-de-passe", name="odysseus_front_user_update_password")
     */
    public function updatePasswordAction(Request $request) {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $this->getUser();
        $form = $this->createForm(new UserType(), $user);
        $form->remove('email');
        $form->remove('receiveSpecialOffers');
        $form->remove('receivePartnersSpecialOffers');
        $form->remove('birthdate');
        $form->remove('infos');
        $form->add('actualPassword', 'password', array(
            'mapped' => false
        ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $isPasswordCorrect = ($encoder->isPasswordValid($user->getPassword(), $form->get('actualPassword')->getData(), $user->getSalt()));

            if (!$isPasswordCorrect) {
                $form->get('actualPassword')->addError(new FormError('Le mot de passe est invalide'));
            }

            if ($form->isValid()) {
                $userManager->updateUser($user);
                $this->get('session')->getFlashBag()->add('odysseus_front_user_update_password', 'Mot de passe enregistrées avec succès');
                return $this->redirect($this->generateUrl('odysseus_front_user_update_password'));
            }
        }
        return $this->render('OdysseusFrontBundle:User:update_password.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Modification mot de passe'),
                    'form' => $form->createView()
        ));
    }

    /**
     * @Route("/mon-compte/ajout-article", name="odysseus_front_user_add_article_model")
     * @Route("/mon-compte/ajout-article/{idArticle}", name="odysseus_front_user_add_article_model_exist")
     */
    public function addArticleModelAction(Request $request, $idArticle = null) {
        $article = new \Odysseus\AdminBundle\Entity\Article();
        $model = new \Odysseus\AdminBundle\Entity\ArticleModel();
        if (is_null($idArticle)) {
            $article->setCreatedAt(new \DateTime());
            $article->setModifiedAt(new \DateTime());
            $article->setUser($this->getUser());
            $article->addModel($model);
        } else {
            $article = $this->getDoctrine()->getManager()->getRepository('OdysseusAdminBundle:Article')->find($idArticle);
        }
        $model->setCreatedAt(new \DateTime());
        $model->setUser($this->getUser());

        $model->addImage(new \Odysseus\AdminBundle\Entity\Image());
        $model->addImage(new \Odysseus\AdminBundle\Entity\Image());
        $model->addImage(new \Odysseus\AdminBundle\Entity\Image());
        $article->addModel($model);
        $model->setArticle($article);
        $form = $this->createForm(new \Odysseus\AdminBundle\Form\ArticleType(), $article);
        $form->remove('models');
        $form->add('models', new \Odysseus\AdminBundle\Form\ArticleModelType(), array(
            'mapped' => false,
            'data' => $model
        ));

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $images = $model->getImage();
            if ($images[0]->file === null) {
                $form->get('models')->get('image')->get(0)->addError(new FormError('Image obligatoire'));
            }
            if ($images[1]->file === null) {
                $model->removeImage($images[1]);
            }
            if ($images[2]->file === null) {
                $model->removeImage($images[2]);
            }
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                $this->get('session')->getFlashBag()->add('odysseus_front_user_add_article_model', 'Votre article a été ajouté avec succès');

                $this->redirect($this->generateUrl('odysseus_front_user_add_article_model', array(
                            'id' => $article->getId()
                )));
            }
        }

        return $this->render('OdysseusFrontBundle:User:add_article_model.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Ajout d\'un article'),
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/mon-compte/mes-produits", name="odysseus_front_user_my_products")
     * @Route("/mon-compte/mes-produits/page/{page}", name="odysseus_front_user_my_products_page")
     */
    public function myProductsAction($page = 1) {
        $user = $this->getUser();
        $ppp = 10;
        $products = $this->getUserProducts($user->getId(), $page, $ppp);
        $count = $this->getUserProductsCount($user->getId());
        return $this->render('OdysseusFrontBundle:User:my_products.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Mes Articles'),
                    'products' => $products,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count),
        ));
    }

    /**
     * @Route("/mon-compte/mes-ventes", name="odysseus_front_user_my_sales")
     * @Route("/mon-compte/mes-ventes/page/{page}", name="odysseus_front_user_my_sales_page")
     */
    public function mySalesAction($page = 1) {
        $user = $this->getUser();
        $ppp = 10;
        $sells = $this->getSells($user->getId(), $page, $ppp);
        $count = $this->getSellsCount($user->getId());
        return $this->render('OdysseusFrontBundle:User:my_sales.html.twig', array(
                    'accountMenu' => $this->getMenu(),
                    'breadcrumb' => $this->getBreadcrumb('Vos ventes'),
                    'sells' => $sells,
                    'count' => $count,
                    'pagination' => $this->getPagination($page, $ppp, $count),
        ));
    }

    private function getMenu() {
        $request = $this->container->get('request');
        $route = $request->get('_route');

        return array(
            (Object) array(
                'label' => 'Récapitulatif',
                'url' => $this->generateURL('odysseus_front_user_my_summary'),
                'isActive' => ($route == 'odysseus_front_user_my_summary'),
                'isVisible' => true
            ),
            (Object) array(
                'label' => 'Mes achats',
                'url' => $this->generateURL('odysseus_front_user_my_orders'),
                'isActive' => ($route == 'odysseus_front_user_my_orders'),
                'isVisible' => true
            ),
            (Object) array(
                'label' => 'Mes ventes',
                'url' => $this->generateURL('odysseus_front_user_my_sales'),
                'isActive' => ($route == 'odysseus_front_user_my_sales'),
                'isVisible' => true
            ),
            (Object) array(
                'label' => 'Mes articles',
                'url' => $this->generateURL('odysseus_front_user_my_products'),
                'isActive' => ($route == 'odysseus_front_user_my_products' || $route == 'odysseus_front_user_my_products_page'),
                'isVisible' => true
            ),
            (Object) array(
                'label' => 'Mes informations personnelles',
                'url' => $this->generateURL('odysseus_front_user_my_personal_informations'),
                'isActive' => ($route == 'odysseus_front_user_my_personal_informations'),
                'isVisible' => true
            ),
            (Object) array(
                'label' => 'Mes coordonnes',
                'url' => $this->generateURL('odysseus_front_user_my_infos'),
                'isActive' => ($route == 'odysseus_front_user_my_infos'),
                'isVisible' => true
            ),
            (Object) array(
                'label' => 'Modification mot de passe',
                'url' => $this->generateURL('odysseus_front_user_update_password'),
                'isActive' => ($route == 'odysseus_front_user_update_password'),
                'isVisible' => true
            )
        );
    }

    private function getBreadcrumb($label = '') {
        $request = $this->container->get('request');
        return array(
            (Object) array(
                'label' => 'Accueil',
                'url' => $request->getBaseUrl()
            ),
            (Object) array(
                'label' => 'Mon compte',
                'url' => $this->generateUrl('odysseus_front_user_my_summary')
            ),
            (Object) array(
                'label' => $label
            )
        );
    }

    private function sendEmailConfirmation($user) {
        $twig = $this->get('twig');


        $template = $twig->loadTemplate('Mail/confirmation.twig');

        $parameters = array(
            'user' => $user,
            'baseurl' => 'http://odysseus.wyden.fr'
        );


        $subject = $template->renderBlock('subject', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);

        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('no-reply@odysseus.com')
                ->setTo($user->getEmail())
                ->setBody($bodyText, 'text/plain')
                ->addPart($bodyHtml, 'text/html')
        ;
        $this->get('mailer')->send($message);
    }

    private function getCommandes($userId, $page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()->getManager()
                        ->getRepository('OdysseusAdminBundle:Order')
                        ->findBy(array('user' => $userId), null, $count, $page * $count);
    }

    private function getCommandesCount($userId) {
        return $this->getDoctrine()->getManager()->getRepository('OdysseusAdminBundle:Order')->createQueryBuilder('a')->select('COUNT(a)')->where('a.user = ' . $userId)->getQuery()->getSingleScalarResult();
    }

    private function getSells($userId, $page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()
                        ->getRepository('OdysseusAdminBundle:OrderArticle')
                        ->createQueryBuilder('oa')
                        ->select('oa')
                        ->join('oa.model', 'm')
                        ->where('m.user = ' . $userId)
                        ->setFirstResult($page * $count)
                        ->setMaxResults($count)->getQuery()->getResult();
    }

    private function getSellsCount($userId) {
        return $this->getDoctrine()
                        ->getRepository('OdysseusAdminBundle:OrderArticle')
                        ->createQueryBuilder('oa')
                        ->select('COUNT(oa)')
                        ->join('oa.model', 'm')
                        ->where('m.user = ' . $userId)
                        ->getQuery()
                        ->getSingleScalarResult();
    }

    private function getUserProducts($userId, $page, $count) {
        $page--;
        if ($page < 0)
            $page = 0;
        return $this->getDoctrine()
                        ->getRepository('OdysseusAdminBundle:ArticleModel')
                        ->createQueryBuilder('am')
                        ->select('am')
                        ->where('am.user = ' . $userId)
                        ->setFirstResult($page * $count)
                        ->setMaxResults($count)->getQuery()->getResult();
    }

    private function getUserProductsCount($userId) {
        return $this->getDoctrine()
                        ->getRepository('OdysseusAdminBundle:ArticleModel')
                        ->createQueryBuilder('am')
                        ->select('COUNT(am)')
                        ->where('am.user = ' . $userId)
                        ->getQuery()
                        ->getSingleScalarResult();
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
