<?php

namespace Odysseus\AdminBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class Menu {

    private $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    private function eqUrl($cUrl, $url) {
        return (strpos($cUrl, $url) === 0);
    }

    private function eqRoute($cUrl, $route) {
        return $this->eqUrl($cUrl, $this->router->generate($route));
    }

    public function getValues(Request $request) {
        $cUrl = $request->getUri();
        $cRoute = $request->get('_route');
        
        return array(
            (Object) array(
                'label' => 'Tableau de bord',
                'icon' => '<span class="glyphicon glyphicon-home"></span>',
                'url' => $this->router->generate('odysseus_admin_dashboard'),
                'isActive' => ($cRoute == 'odysseus_admin_dashboard'),
                'items' => array()
            ),
            (Object) array(
                'label' => 'Articles',
                'icon' => '<span class="glyphicon glyphicon-list"></span>',
                'url' => $this->router->generate('odysseus_admin_article'),
                'isActive' => $this->eqRoute($cUrl, 'odysseus_admin_article'),
                'items' => array(
                    (Object) array(
                        'label' => 'Voir les articles',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_article'),
                        'isActive' => ($cRoute == 'odysseus_admin_article'),
                        'items' => array()
                    ),
                    (Object) array(
                        'label' => 'Voir les catégories',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_category'),
                        'isActive' => ($cRoute == 'odysseus_admin_category'),
                        'items' => array()
                    ),
                    (Object) array('separator' => true),
                    (Object) array(
                        'label' => 'Statistique',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_dashboard'),
                        'isActive' => ($cRoute == 'odysseus_admin_dashboard'),
                        'items' => array()
                    ),
                )
            ),
            (Object) array(
                'label' => 'Commandes',
                'icon' => '<span class="glyphicon glyphicon-shopping-cart"></span>',
                'url' => $this->router->generate('odysseus_admin_order'),
                'isActive' => $this->eqRoute($cUrl, 'odysseus_admin_order'),
                'items' => array(
                    (Object) array(
                        'label' => 'Voir les commandes',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_order'),
                        'isActive' => ($cRoute == 'odysseus_admin_order'),
                        'items' => array()
                    ),
                    (Object) array('separator' => true),
                    (Object) array(
                        'label' => 'Statistique',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_dashboard'),
                        'isActive' => ($cRoute == 'odysseus_admin_dashboard'),
                        'items' => array()
                    ),
                )
            ),
            (Object) array(
                'label' => 'Utilisateurs',
                'icon' => '<span class="glyphicon glyphicon-user"></span>',
                'url' => $this->router->generate('odysseus_admin_user'),
                'isActive' => $this->eqRoute($cUrl, 'odysseus_admin_user'),
                'items' => array(
                    (Object) array(
                        'label' => 'Voir les utilisateurs',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_user'),
                        'isActive' => ($cRoute == 'odysseus_admin_user'),
                        'items' => array()
                    ),
                    (Object) array(
                        'label' => 'Nouvel utilisateur',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_user_create'),
                        'isActive' => ($cRoute == 'odysseus_admin_user_create'),
                        'items' => array()
                    ),
                    (Object) array('separator' => true),
                    (Object) array(
                        'label' => 'Statistique',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_dashboard'),
                        'isActive' => ($cRoute == 'odysseus_admin_dashboard'),
                        'items' => array()
                    ),
                )
            ),
            (Object) array(
                'label' => 'Pages',
                'icon' => '<span class="glyphicon glyphicon-file"></span>',
                'url' => $this->router->generate('odysseus_admin_page'),
                'isActive' => $this->eqRoute($cUrl, 'odysseus_admin_page'),
                'items' => array(
                    (Object) array(
                        'label' => 'Voir les pages',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_page'),
                        'isActive' => ($cRoute == 'odysseus_admin_page'),
                        'items' => array()
                    ),
                    (Object) array(
                        'label' => 'Nouvelle page',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_dashboard'),
                        'isActive' => ($cRoute == 'odysseus_admin_dashboard'),
                        'items' => array()
                    ),
                    (Object) array('separator' => true),
                    (Object) array(
                        'label' => 'Statistique',
                        'icon' => null,
                        'url' => $this->router->generate('odysseus_admin_dashboard'),
                        'isActive' => ($cRoute == 'odysseus_admin_dashboard'),
                        'items' => array()
                    ),
                )
            ),
            (Object) array(
                'label' => 'Réglages',
                'icon' => '<span class="glyphicon glyphicon-cog"></span>',
                'url' => $this->router->generate('odysseus_admin_dashboard'),
                'isActive' => false,
                'items' => array()
            )
        );
    }

}
