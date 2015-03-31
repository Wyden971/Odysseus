<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController {

    public function renderLogin(array $data) {
        $requestAttributes = $this->container->get('request')->attributes;
        
        if ($requestAttributes->get('_route') == 'fos_user_security_admin_login') {
            $template = sprintf('OdysseusAdminBundle:Security:login.html.twig');
        } else {
            $template = sprintf('OdysseusFrontBundle:Security:login.html.twig');
        }
        return $this->container->get('templating')->renderResponse($template, $data);
    }

}