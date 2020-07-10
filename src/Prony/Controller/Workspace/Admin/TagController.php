<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Admin;

use Prony\Entity\Board;
use Prony\Entity\Tag;
use Prony\Form\Type\CreateTagType;
use Prony\Form\Type\UpdateTagType;
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
class TagController extends AbstractController
{
    /** @var ManagerInterface */
    private $boardManager;

    /** @var ManagerInterface */
    private $tagManager;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(ManagerInterface $boardManager, ManagerInterface $tagManager, WorkspaceProvider $workspaceProvider)
    {
        $this->boardManager = $boardManager;
        $this->tagManager = $tagManager;
        $this->workspaceProvider = $workspaceProvider;
    }

    /**
     * @Route("/board/{slug}/tags")
     */
    public function list(Board $board, Request $request): Response
    {
        return $this->render('workspace/admin/tag/list.html.twig', [
            'board' => $board,
        ]);
    }

    /**
     * @Route("/board/{board_slug}/tag")
     * @ParamConverter("board", options={"mapping": {"board_slug": "slug"}})
     */
    public function create(Board $board, Request $request): Response
    {
        $form = $this->createForm(CreateTagType::class, $this->tagManager->create());
        $form->getData()->setBoard($board);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->tagManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.tag.create');

                return new RedirectResponse($this->container->get('router')->generate('prony_frontend_company_tag_list', ['slug' => $board->getSlug()]));
            }
        }

        return $this->render('workspace/admin/board/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/board/{board_slug}/tag/{slug}")
     * @ParamConverter("board", options={"mapping": {"board_slug": "slug"}})
     */
    public function update(Board $board, Tag $tag, Request $request): Response
    {
        $form = $this->createForm(UpdateTagType::class, $tag);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->tagManager->update($form->getData(), true);
                $this->addFlash('success', 'prony.flash.tag.update');

                return new RedirectResponse($this->container->get('router')->generate('prony_frontend_company_tag_list', ['slug' => $board->getSlug()]));
            }
        }

        return $this->render('workspace/admin/tag/update.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    /**
     * @Route("/board/{board_slug}/tag/{slug}/delete")
     * @ParamConverter("board", options={"mapping": {"board_slug": "slug"}})
     */
    public function delete(Board $board, Tag $tag, Request $request): Response
    {
        $this->tagManager->remove($tag);
        $this->addFlash('success', 'prony.flash.tag.delete');

        return new RedirectResponse($this->container->get('router')->generate('prony_frontend_company_tag_list', ['slug' => $board->getSlug()]));
    }
}
