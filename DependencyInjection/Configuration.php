<?php

/*
 * This file is part of the Botbund package.
 *
 * (c) balzacLeGeek <https://balzacLeGeek.github.io/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace balzacLeGeek\BotbundBundle\DependencyInjection;

use balzacLeGeek\BotbundBundle\Utils\Config;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('botbund');

        if (method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // For symfony/config < 4.2
            $rootNode = $treeBuilder->root('botbund');
        }

        $rootNode
            ->children()
                ->scalarNode(Config::FB_API_VERSION_KEY)
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode(Config::ACCESS_TOKEN_KEY)
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
                ->scalarNode(Config::VERIFY_TOKEN_KEY)
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
