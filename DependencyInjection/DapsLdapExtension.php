<?php

namespace Daps\LdapBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class DapsLdapExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('daps_ldap.ldap.host', $config['host']);
        $container->setParameter('daps_ldap.ldap.port', $config['port']);
        $container->setParameter('daps_ldap.ldap.dn', $config['dn']);
        $container->setParameter('daps_ldap.ldap.username_suffix', $config['username_suffix']);
        $container->setParameter('daps_ldap.ldap.version', $config['version']);
        $container->setParameter('daps_ldap.ldap.usessl', $config['use_ssl']);
        $container->setParameter('daps_ldap.ldap.usestarttls', $config['use_start_tls']);
        $container->setParameter('daps_ldap.ldap.optrefs', $config['opt_referrals']);
        $container->setParameter('daps_ldap.ldap.admin.enable', $config['admin_login']);
        $container->setParameter('daps_ldap.ldap.admin.dn', $config['admin_user']);
        $container->setParameter('daps_ldap.ldap.admin.password', $config['admin_pass']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
