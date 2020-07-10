<?php

declare(strict_types=1);

namespace Prony\Controller\Site\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(host="%domain%")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(Request $request): Response
    {
        return $this->render('site/front/index/index.html.twig');
    }

    /**
     * @Route("/pricing")
     */
    public function pricing(Request $request): Response
    {
        return $this->render('site/front/index/pricing.html.twig');
    }

    /**
     * @Route("/terms")
     */
    public function terms(Request $request): Response
    {
        return $this->render('site/front/index/terms.html.twig');
    }

    /**
     * @Route("/privacy")
     */
    public function privacy(Request $request): Response
    {
        return $this->render('site/front/index/privacy.html.twig');
    }
}
