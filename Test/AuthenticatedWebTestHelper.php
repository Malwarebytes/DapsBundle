<?php
/**
 *
 * This file is part of the DapsBundle package.
 *
 * Copyright (c) 2012 Malwarebytes
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Daps\LdapBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


class AuthenticatedWebTestHelper extends WebTestCase
{

    /**
     * @param $firewallName
     * @param array $options  passed to createClient()
     * @param array $server   passed to createClient()
     * @param array $roles    the roles that the user should be logged in with
     * @return \Symfony\Component\BrowserKit\Client
     */
    public static function createClientWithLDAPAuthentication($firewallName, array $options = array(), array $server = array(),array $roles=array('ROLE_USER', 'ROLE_USER_ALL','ROLE_USER_IT'))
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
    public static function createClientWithAuthentication($firewallName, array $options = array(), array $server = array(), \Symfony\Component\Security\Core\User\UserInterface $user=null)
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
