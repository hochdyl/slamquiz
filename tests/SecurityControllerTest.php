<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityControllerTest extends WebTestCase
{
    private function logIn($userName,$userRole) {

        $session = $this->client->getContainer()->get('session');

        $firewallName = 'main';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'main';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
        $token = new UsernamePasswordToken('user', null, $firewallName, [$userRole]);
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }

    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Login form show username, password and submit button
     */
    public function testShowLogin()
    {
        // Request /login 
        $crawler = $this->client->request('GET', '/login');

        // Asserts that /login path exists and don't return an error
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
       
        
        // Asserts that the phrase "Log in!" is present in the page's title
        $this->assertSelectorTextContains('html title', 'Log in!');

        // Asserts that the response content contains 'csrf token'
        $this->assertContains('type="hidden" name="_csrf_token"',
        $this->client->getResponse()->getContent());

        
        // Asserts that the response content contains 'input type="text" id="inputEmail"'
        $this->assertContains('<input type="email" value="" name="email" id="inputEmail" class="form-control" placeholder="Email" required autofocus>',
        $this->client->getResponse()->getContent());
        
        // Asserts that the response content contains 'input type="text" id="inputPassword"'
        $this->assertContains('<input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>',
        $this->client->getResponse()->getContent());
    }

    /**
     * Verify that the category list is not displayed to users who do not have the admin role
     */
    public function testNotShowCategory()
    {
        // Request /category 
        $this->client->request('GET', '/category');

        // Asserts that category path move to another path (login) /* 
        $this->assertEquals(301, $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredRoleUser() {

        $this->logIn('user', 'ROLE_USER');
        
        
        // Asserts that /category path exists and don't return an error
        $crawler = $this->client->request('GET', '/category/');
        /* 
        Ecrire ici le code pour vérifier que, si l'utilisateur est connecté avec le rôle ROLE_USER, 
        la requête '/category' renvoie une réponse HTTP avec un code de statut égale à 200 (Response::HTTP_OK)
        */
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        // Asserts that the response content contains 'Category index' in 'h1' tag
        $this->assertSelectorTextContains('html title', 'Category index');
        /* 
        Ecrire ici le code pour vérifier que, si l'utilisateur est connecté avec le rôle ROLE_USER, 
        la requête '/category' renvoie 'Category index' dans la balise 'h1'
        */

        // Asserts that /category/new path exists and don't return an error
        $crawler = $this->client->request('GET', '/category/new');
        /* 
        Ecrire ici le code pour vérifier que, si l'utilisateur est connecté avec le rôle ROLE_USER, 
        la requête '/category/new' affiche que l'accès est interdit
        c'est à dire affirmer que le code de statut de la réponse est égale à 403 (Response::HTTP_FORBIDDEN)
        */
        $this->assertSame(Response::HTTP_FORBIDDEN, $this->client->getResponse()->getStatusCode());
    }

    public function testSecuredRoleAdmin()
    {
        $this->logIn('admin', 'ROLE_ADMIN');
        
        // Asserts that /category/new path exists and don't return an error
        $crawler = $this->client->request('GET', '/category/new');
        /* 
        Ecrire ici le code pour vérifier que, si l'utilisateur est connecté avec le rôle ROLE_ADMIN, 
        la requête '/category/new' renvoie une réponse HTTP avec un code de statut égale à 200 (Response::HTTP_OK)
        */
        $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        
        // Asserts that the response content contains 'Create new category' in 'h1' tag
        $this->assertSelectorTextContains('html title', 'New category');
        /* 
        Ecrire ici le code pour vérifier que, si l'utilisateur est connecté avec le rôle ROLE_ADMIN, 
        la requête '/category/new' renvoie 'Create new category' dans la balise 'h1'
        */
    }       
}
