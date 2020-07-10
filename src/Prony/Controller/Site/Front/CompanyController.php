<?php

declare(strict_types=1);

namespace Prony\Controller\Site\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/company", host="%domain%")
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/about")
     */
    public function about(Request $request): Response
    {
        return $this->render('site/front/company/about.html.twig');
    }

    /**
     * @Route("/why-prony")
     */
    public function why(Request $request): Response
    {
        return $this->render('site/front/company/why-prony.html.twig');
    }
}
