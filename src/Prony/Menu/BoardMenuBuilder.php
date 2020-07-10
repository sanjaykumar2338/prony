<?php

declare(strict_types=1);

namespace Prony\Menu;

use Knp\Menu\FactoryInterface;
use Prony\Manager\BoardManager;

final class BoardMenuBuilder
{
    /** @var FactoryInterface */
    private $factory;

    /** @var BoardManager */
    private $boardManager;

    public function __construct(FactoryInterface $factory, BoardManager $boardManager)
    {
        $this->factory = $factory;
        $this->boardManager = $boardManager;
    }

    public function createBoardMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
        $menu
            ->addChild('prony.menu.company.settings', [
                'route' => 'prony_workspace_admin_index_settings',
            ])
            ->setExtra('translation_domain', 'menu');

        $this->boardManager->findAllBoard();
        foreach ($this->boardManager->findAllBoard() as $board) {
            $boardMain = $menu->addChild($board->getName());
            $boardMain
                ->addChild('prony.menu.board.general', [
                    'route' => 'prony_workspace_admin_board_update',
                    'routeParameters' => ['slug' => $board->getSlug()],
                ])
                ->setExtra('translation_domain', 'menu');
            $boardMain
                ->addChild('prony.menu.board.tags', [
                    'route' => 'prony_workspace_admin_tag_list',
                    'routeParameters' => ['slug' => $board->getSlug()],
                ])
                ->setExtra('translation_domain', 'menu');
            $boardMain
                ->addChild('prony.menu.board.statuses', [
                    'route' => 'prony_workspace_admin_status_list',
                    'routeParameters' => ['slug' => $board->getSlug()],
                ])
                ->setExtra('translation_domain', 'menu');
            $boardMain
                ->addChild('prony.menu.board.post_form', [
                    'route' => 'prony_workspace_admin_postform_edit',
                    'routeParameters' => ['slug' => $board->getSlug()],
                ])
                ->setExtra('translation_domain', 'menu');
        }

        return $menu;
    }
}
