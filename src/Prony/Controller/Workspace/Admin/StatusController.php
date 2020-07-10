<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Admin;

use Prony\Entity\Board;
use Prony\Entity\Status;
use Prony\Form\Type\CreateStatusType;
use Prony\Form\Type\UpdateStatusType;
use Prony\Provider\WorkspaceProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Resource\Manager\ManagerInterface;

/**
 * @Route("/workspace")
 */
class StatusController extends AbstractController
{
    /** @var ManagerInterface */
    private $boardManager;

    /** @var ManagerInterface */
    private $statusManager;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(ManagerInterface $boardManager, ManagerInterface $statusManager, WorkspaceProvider $workspaceProvider)
    {
        $this->boardManager = $boardManager;
        $this->statusManager = $statusManager;
        $this->workspaceProvider = $workspaceProvider;
    }

    /**
     * @Route("/board/{slug}/statuses")
     */
    public function list(Board $board, Request $request): Response
    {
        return $this->render('workspace/admin/status/list.html.twig', [
            'board' => $board,
        ]);
    }

    /**
     * @Route("/board/{board_slug}/status")
     * @ParamConverter("board", options={"mapping": {"board_slug": "slug"}})
     */
    public function create(Board $board, Request $request): Response
    {
        $form = $this->createForm(CreateStatusType::class, $this->statusManager->create());
        $form->getData()->setBoard($board);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->statusManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.status.create');

                return new RedirectResponse($this->container->get('router')->generate('prony_frontend_company_status_list', ['slug' => $board->getSlug()]));
            }
        }

        return $this->render('workspace/admin/status/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/board/{board_slug}/status/{slug}")
     * @ParamConverter("board", options={"mapping": {"board_slug": "slug"}})
     */
    public function update(Board $board, Status $status, Request $request): Response
    {
        $form = $this->createForm(UpdateStatusType::class, $status);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->statusManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.status.update');

                return new RedirectResponse($this->container->get('router')->generate('prony_frontend_company_status_list', ['slug' => $board->getSlug()]));
            }
        }

        return $this->render('workspace/admin/status/update.html.twig', [
            'form' => $form->createView(),
            'status' => $status,
        ]);
    }

    /**
     * @Route("/board/{board_slug}/status/{slug}/delete")
     * @ParamConverter("board", options={"mapping": {"board_slug": "slug"}})
     */
    public function delete(Board $board, Status $status, Request $request): Response
    {
        $this->statusManager->remove($status);
        $this->addFlash('success', 'prony.flash.status.delete');

        return new RedirectResponse($this->container->get('router')->generate('prony_frontend_company_status_list', ['slug' => $board->getSlug()]));
    }
}
