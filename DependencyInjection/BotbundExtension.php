<?php

namespace balzacLeGeek\BotbundBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class BotbundExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
     {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $botbundConfig = $configs[0];

        $container->setParameter('botbund.fb_api_version', $botbundConfig['fb_api_version']);
        $container->setParameter('botbund.access_token', $botbundConfig['access_token']);
        $container->setParameter('botbund.verify_token', $botbundConfig['verify_token']);
    }
}
