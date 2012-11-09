<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Jonathan Chan <jchan@malwarebytes.org>
 * Date: 11/9/12
 * Time: 3:24 PM
 */

namespace Daps\LdapBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class AuthenticatedWebTestCase extends WebTestCase
{

    /**
     * @param $firewallName
     * @param array $options  passed to createClient()
     * @param array $server   passed to createClient()
     * @param array $roles    the roles that the user should be logged in with
     * @return \Symfony\Component\BrowserKit\Client
     */
    protected static function createClientWithLDAPAuthentication($firewallName, array $options = array(), array $server = array(),array $roles=array('ROLE_USER', 'ROLE_USER_ALL','ROLE_USER_IT'))
    {
        $user = new \Daps\LdapBundle\Security\User\LdapUser("test","", $roles);

        return static::createClientWithAuthentication($firewallName,$options,$server,$user);
    }


    /**
     * @param $firewallName
     * @param array $options  passed to createClient()
     * @param array $server   passed to createClient()
     * @param \Symfony\Component\Security\Core\User\UserInterface $user defaults to a user with array('ROLE_USER','ROLE_ADMIN') roles
     * @return \Symfony\Component\BrowserKit\Client
     */
    protected static function createClientWithAuthentication($firewallName, array $options = array(), array $server = array(), \Symfony\Component\Security\Core\User\UserInterface $user=null)
    {
        $client = static::createClient($options, $server);

        $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie(session_name(), false));

        // dummy call to bypass the hasPreviousSession check
        $crawler = $client->request('GET', '/');

        if (is_null($user)) {
            $user = new \Symfony\Component\Security\Core\User\User("TEST_USER","",array('ROLE_USER','ROLE_ADMIN'));
        }

        $token=new UsernamePasswordToken($user, null,$firewallName,$user->getRoles());
        $client->getContainer()->get('security.context')->setToken($token);

        $session = $client->getContainer()->get('session');
        $session->set('_security_' . $firewallName, serialize($token));
        $session->save();



        return $client;
    }


}
