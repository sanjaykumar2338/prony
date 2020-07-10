<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Admin;

use Prony\Entity\Board;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/workspace")
 */
class PostFormController extends AbstractController
{
    /**
     * @Route("/board/{slug}/form")
     */
    public function edit(Board $board, Request $request): Response
    {
        return $this->render('workspace/admin/post-form/edit.html.twig', [
            'board' => $board,
        ]);
    }
}
