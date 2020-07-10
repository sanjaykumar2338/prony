<?php

declare(strict_types=1);

namespace Prony\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Prony\Entity\Workspace;
use Prony\Provider\DefaultStatusProvider;
use Symfony\Contracts\Translation\TranslatorInterface;

final class WorkspaceCreationListener
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var DefaultStatusProvider */
    private $statusProvider;

    public function __construct(DefaultStatusProvider $statusProvider, TranslatorInterface $translator)
    {
        $this->statusProvider = $statusProvider;
        $this->translator = $translator;
    }

    public function prePersist(Workspace $workspace, LifecycleEventArgs $event): void
    {
        $language = $workspace->getLanguage();
        $workspace->setRoadMapTitle($this->translator->trans('prony.entity.workspace.road_map.title', [], 'entity', $language));
        $workspace->setBoardListTitle($this->translator->trans('prony.entity.workspace.board_list.title', [], 'entity', $language));
        foreach ($this->statusProvider->getDefaultStatusList($language) as $status) {
            $status->setWorkspace($workspace);
        }
    }
}
