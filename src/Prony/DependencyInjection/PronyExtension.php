<?php

declare(strict_types=1);

namespace Prony\DependencyInjection;

use Prony\Search\EventListener\PostSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Talav\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;

class PronyExtension extends AbstractResourceExtension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->registerResources('app', $config['resources'], $container);

        $container->setParameter('prony.elasticsearch.enabled', $config['elasticsearch']['enabled']);
        $this->elasticSearchConfig($config, $container);
    }

    public function getAlias()
    {
        return 'prony';
    }

    private function elasticSearchConfig($config, ContainerBuilder $container): void
    {
        // This does not work unfortunately.
        // Need to figure out a better solution https://etepia.atlassian.net/browse/PRON-24
        if (!$config['elasticsearch']['enabled']) {
            $container->removeDefinition(PostSubscriber::class);
        }
    }
}
