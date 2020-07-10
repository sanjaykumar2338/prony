<?php

declare(strict_types=1);

namespace Prony\Controller\Site\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", host="%domain%")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(Request $request): Response
    {
        return $this->render('site/front/product/index.html.twig');
    }

    /**
     * @Route("/features")
     */
    public function features(Request $request): Response
    {
        return $this->render('site/front/product/features.html.twig');
    }

    /**
     * @Route("/integrations")
     */
    public function integrations(Request $request): Response
    {
        return $this->render('site/front/product/integrations.html.twig');
    }
}
