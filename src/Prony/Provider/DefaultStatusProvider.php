<?php

declare(strict_types=1);

namespace Prony\Provider;

use Prony\Entity\Status;
use Symfony\Contracts\Translation\TranslatorInterface;
use Talav\Component\Resource\Manager\ManagerInterface;

final class DefaultStatusProvider
{
    private ManagerInterface $statusManager;

    /** @var TranslatorInterface */
    private $translator;

    private array $names = [
        'prony.entity.status.open',
        'prony.entity.status.under_review',
        'prony.entity.status.planned',
        'prony.entity.status.in_progress',
        'prony.entity.status.complete',
        'prony.entity.status.closed',
    ];

    public function __construct(ManagerInterface $statusManager, TranslatorInterface $translator)
    {
        $this->statusManager = $statusManager;
        $this->translator = $translator;
    }

    /** @return array|Status[] */
    public function getDefaultStatusList(string $language): array
    {
        $return = [];
        foreach ($this->names as $position => $label) {
            /** @var Status $status */
            $status = $this->statusManager->create();
            $status->setPosition($position);
            $status->setName($this->translator->trans($label, [], 'entity', $language));
            $return[] = $status;
        }

        return $return;
    }
}
