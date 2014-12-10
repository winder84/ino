<?php

namespace Wdr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WdrAdminBundle:Default:index.html.twig');
    }
}
