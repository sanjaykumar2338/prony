<?php

declare(strict_types=1);

namespace Prony\Search\EventListener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use ONGR\ElasticsearchBundle\Service\IndexService;
use Prony\Entity\Post;
use Prony\Search\Document\PostDoc;

class PostSubscriber implements EventSubscriberInterface
{
    protected IndexService $indexService;

    protected bool $enabled;

    public function __construct(IndexService $indexService, bool $enabled)
    {
        $this->indexService = $indexService;
        $this->enabled = $enabled;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        if (!$this->enabled) {
            return;
        }
        $this->updateSearch($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        if (!$this->enabled) {
            return;
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        if (!$this->enabled) {
            return;
        }
        $this->updateSearch($args);
    }

    private function updateSearch(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if (!$entity instanceof Post) {
            return;
        }
        $postDoc = new PostDoc();
        $postDoc->setId($entity->getId()->__toString());
        $postDoc->setTitle($entity->getTitle());
        $postDoc->setDescription($entity->getDescription());
        $postDoc->setWorkspace($entity->getBoard()->getWorkspace()->getId()->__toString());
        $postDoc->setCreatedAt($entity->getCreatedAt());
        $this->indexService->persist($postDoc);
        $this->indexService->commit();
    }
}
