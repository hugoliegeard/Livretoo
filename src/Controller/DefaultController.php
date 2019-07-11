<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * Page d'Accueil
     * @Route("/", name="default_home", methods={"GET"})
     * @IsGranted("ROLE_MANAGER")
     */
    public function home()
    {
        return $this->render('default/index.html.twig');
    }

}
