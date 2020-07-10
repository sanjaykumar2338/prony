<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Admin;

use Prony\Entity\Board;
use Prony\Form\Type\CreateBoardType;
use Prony\Form\Type\UpdateBoardType;
use Prony\Provider\WorkspaceProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Resource\Manager\ManagerInterface;

/**
 * @Route("/workspace")
 */
class BoardController extends AbstractController
{
    /** @var ManagerInterface */
    private $boardManager;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(ManagerInterface $boardManager, WorkspaceProvider $workspaceProvider)
    {
        $this->boardManager = $boardManager;
        $this->workspaceProvider = $workspaceProvider;
    }

    /**
     * @Route("/board/create")
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(CreateBoardType::class, $this->boardManager->create());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->boardManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.board.create');

                return new RedirectResponse($this->container->get('router')->generate('prony_workspace_admin_index_dashboard'));
            }
        }

        return $this->render('workspace/admin/board/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/board/{slug}/settings/general")
     */
    public function update(Board $board, Request $request): Response
    {
        $form = $this->createForm(UpdateBoardType::class, $board);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->boardManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.board.update');

                return new RedirectResponse($this->container->get('router')->generate('prony_workspace_admin_board_update', ['slug' => $board->getSlug()]));
            }
        }

        return $this->render('workspace/admin/board/update.html.twig', [
            'form' => $form->createView(),
            'board' => $board,
        ]);
    }
}
