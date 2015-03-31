<?php

namespace Odysseus\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * @Route("/git")
 */
class GitController extends Controller
{
    /**
    * @Route("/", name="odysseus_git")
    */
    public function indexAction()
    {
        return $this->render('OdysseusAdminBundle:Git:index.html.twig');
    }
    
    /**
    * @Route("/pull", name="odysseus_git_pull")
    */
    public function pullAction()
    {
        $connection = ssh2_connect('localhost', 22);
        ssh2_auth_password($connection, 'root', '9fgvuvNsWVex');
        
        $stdout_stream = ssh2_exec($connection, 'cd /var/www/odysseus.wyden.fr/web/ && git pull');
        
        //$err_stream = ssh2_fetch_stream($stdout_stream, SSH2_STREAM_STDERR);
        $dio_stream = ssh2_fetch_stream($stdout_stream, SSH2_STREAM_STDIO);
        
        //stream_set_blocking($err_stream, true);
        stream_set_blocking($dio_stream, true);
        
        //$result_err = stream_get_contents($err_stream);
        $result_dio = stream_get_contents($dio_stream);

        return new Response($result_dio, 200, array('content-type' => 'text/plain'));
    }
}
