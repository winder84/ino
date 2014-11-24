<?php

namespace Wdr\InowebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WdrInowebBundle:Default:index.html.twig');
    }
}
