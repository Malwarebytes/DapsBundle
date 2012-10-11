<?php

namespace Daps\LdapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('daps_ldap');

        $rootNode
            ->children()
                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('port')->cannotBeEmpty()->defaultValue(389)->end()
                ->scalarNode('dn')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('username_suffix')->defaultValue('')->end()
                ->scalarNode('version')->defaultValue(3)->end()
                ->booleanNode('use_ssl')->defaultFalse()->end()
                ->booleanNode('use_start_tls')->defaultFalse()->end()
                ->scalarNode('opt_referrals')->defaultFalse()->end()
                ->booleanNode('admin_login')->defaultFalse()->end()
                ->scalarNode('admin_user')->defaultValue('')->end()
                ->scalarNode('admin_pass')->defaultValue('')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
