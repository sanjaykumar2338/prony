<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Front;

use Prony\Entity\Board;
use Prony\Entity\Post;
use Prony\Entity\Workspace;
use Prony\Provider\WorkspaceProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Resource\Manager\ManagerInterface;

class IndexController extends AbstractController
{
    /** @var ManagerInterface */
    private $boardManager;

    /** @var ManagerInterface */
    private $tagManager;

    /** @var ManagerInterface */
    private $postManager;

    /** @var ManagerInterface */
    private $commentManager;

    /** @var WorkspaceProvider */
    private $workspaceProvider;

    public function __construct(
        ManagerInterface $boardManager,
        ManagerInterface $tagManager,
        ManagerInterface $postManager,
        ManagerInterface $commentManager,
        WorkspaceProvider $workspaceProvider
    ) {
        $this->boardManager = $boardManager;
        $this->tagManager = $tagManager;
        $this->workspaceProvider = $workspaceProvider;
        $this->postManager = $postManager;
        $this->commentManager = $commentManager;
    }

    /**
     * @Route("/")
     * @ParamConverter("workspace", converter="workspace_converter")
     */
    public function index(Workspace $workspace, Request $request): Response
    {
        return $this->render('workspace/front/index/index.html.twig', [
            'workspace' => $workspace,
        ]);
    }

    /**
     * @Route("/board/{slug}")
     * @ParamConverter("workspace", converter="workspace_converter")
     */
    public function board(Workspace $workspace, Board $board, Request $request): Response
    {
        return $this->render('workspace/front/index/board.html.twig', [
            'workspace' => $workspace,
            'board' => $board,
            'posts' => $this->postManager->getRepository()->getBoardPosts($board),
        ]);
    }

    /**
     * @Route("/board/{slug}/{post_slug}")
     * @ParamConverter("post", options={"mapping": {"post_slug": "slug"}})
     */
    public function post(Workspace $workspace, Board $board, Post $post, Request $request): Response
    {
        return $this->render('workspace/front/index/post.html.twig', [
            'workspace' => $workspace,
            'board' => $board,
            'post' => $post,
            'comments' => $this->commentManager->getRepository()->getCommentTree($post->getRootComment()),
        ]);
    }
}
