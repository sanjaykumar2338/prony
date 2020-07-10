<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Admin;

use Prony\Form\Type\CompanySettingsType;
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
class IndexController extends AbstractController
{
    /** @var ManagerInterface */
    private $workspaceManager;

    /** @var ManagerInterface */
    private $boardManager;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(
        ManagerInterface $workspaceManager,
        ManagerInterface $boardManager,
        WorkspaceProvider $workspaceProvider
    ) {
        $this->workspaceManager = $workspaceManager;
        $this->boardManager = $boardManager;
        $this->workspaceProvider = $workspaceProvider;
    }

    /**
     * @Route("/")
     */
    public function dashboard(Request $request): Response
    {
        return $this->render('workspace/admin/index/dashboard.html.twig', [
            'boards' => $this->boardManager->getRepository()->findBy(['workspace' => $this->workspaceProvider->getWorkspace()], ['position' => 'ASC']),
        ]);
    }

    /**
     * @Route("/settings")
     */
    public function settings(Request $request): Response
    {
        $workspace = $this->workspaceProvider->getWorkspace();
        $form = $this->createForm(CompanySettingsType::class, $workspace);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->workspaceManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.company.update');

                return new RedirectResponse($this->container->get('router')->generate('prony_workspace_admin_index_settings'));
            }
        }

        return $this->render('workspace/admin/index/settings.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
