<?php

declare(strict_types=1);

namespace Prony\Controller\Site\Front;

use Prony\Entity\Workspace;
use Prony\Form\Type\Workspace\DeleteWorkspaceType;
use Prony\Form\Type\Workspace\EditWorkspaceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Resource\Manager\ManagerInterface;

/**
 * @Route("/client/workspace", host="%domain%")
 */
class WorkspaceController extends AbstractController
{
    private ManagerInterface $workspaceManager;

    private array $params;

    public function __construct(ManagerInterface $workspaceManager, array $params)
    {
        $this->workspaceManager = $workspaceManager;
        $this->params = $params;
    }

    /**
     * @Route("/")
     */
    public function list(Request $request): Response
    {
        $workspaces = $this->workspaceManager->getRepository()->findBy(['createdBy' => $this->getUser()], ['name' => 'ASC']);

        return $this->render('site/front/workspace/list.html.twig', [
            'workspaces' => $workspaces,
            'defaultDomain' => $this->params['domain'] ?? '',
        ]);
    }

    /**
     * @Route("/add")
     */
    public function add(Request $request): Response
    {
        return $this->render('site/front/workspace/add.html.twig');
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit(Workspace $workspace, Request $request): Response
    {
        $options = ['validation_groups' => ['editing']];
        $form = $this->createForm(EditWorkspaceType::class, $workspace, $options);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->workspaceManager->update($form->getData(), true);
            $this->addFlash('success', 'prony.flash.workspace.update');

            return $this->redirectToRoute('prony_site_front_workspace_list');
        }

        return $this->render('site/front/workspace/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete(Workspace $workspace, Request $request): Response
    {
        $form = $this->createForm(DeleteWorkspaceType::class, $workspace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->workspaceManager->remove($workspace);
            $this->addFlash('success', 'prony.flash.workspace.delete');

            return $this->redirectToRoute('prony_site_front_workspace_list');
        }

        return $this->render('site/front/workspace/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
