<?php

namespace Wdr\InowebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		$services = $em->getRepository('WdrInowebBundle:Service')->findAll();

		return $this->render('WdrInowebBundle:Default:index.html.twig', array(
			'services' => $services
		));
    }
}
