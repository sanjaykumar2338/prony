<?php

declare(strict_types=1);

namespace Prony\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Talav\Component\Resource\Manager\ManagerInterface;

class MediaPostLoadEventListener
{
//    /** @var ManagerInterface */
//    private $mediaManager;

//    public function __construct(ManagerInterface $mediaManager)
//    {
//        $this->mediaManager = $mediaManager;
//    }

    public function postLoad(LifecycleEventArgs $args)
    {
//        $class = $this->mediaManager->getClassName();
//        $media = $args->getEntity();
//        if ($media instanceof $class && $media->getProviderName()) {
//            /** @var MediaProviderInterface $provider */
//            $provider = $this->pool->getProvider($media->getProviderName());
//            foreach ($provider->getFormats() AS $key => $defintion) {
//                // hack so we do not show admin formats
//                if ($key === 'admin') {
//                    return;
//                }
//                list($context, $formatName) = explode('_', $key);
//                if ($context != $media->getContext()) {
//                    continue;
//                }
//                $format = $provider->getHelperProperties($media, $key);
//                $format['context'] = $context;
//                $format['format'] = $formatName;
//                unset($format['srcset']);
//                $media->addFormat($format);
//            }
//        }
    }
}
