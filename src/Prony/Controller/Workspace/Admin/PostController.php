<?php

declare(strict_types=1);

namespace Prony\Controller\Workspace\Admin;

use Prony\Entity\Workspace;
use Prony\Search\Finder\PostFinder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Talav\Component\Resource\Repository\RepositoryInterface;

/**
 * @Route("/workspace/post")
 */
class PostController extends AbstractController
{
    protected PostFinder $postFinder;

    protected RepositoryInterface $postRepository;

    public function __construct(PostFinder $postFinder, RepositoryInterface $postRepository)
    {
        $this->postFinder = $postFinder;
        $this->postRepository = $postRepository;
    }

    /**
     * @Route("/list")
     * @ParamConverter("workspace", converter="workspace_converter")
     */
    public function list(Workspace $workspace, Request $request): Response
    {
        $page = (int) $request->get('page', 1);
        $pager = $this->postFinder->findByWorkspace($workspace, $page);

        return $this->render('workspace/admin/post/list.html.twig', [
            'posts' => $this->postRepository->getOrderedPosts($pager),
            'pager' => $pager,
        ]);
    }
}
