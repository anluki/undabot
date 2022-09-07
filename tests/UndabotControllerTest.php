<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Manager\UndabotManager;

class UndabotControllerTest extends  WebTestCase {

    public function testGet() {
        $client = self::createClient();
        $client->request('GET', '/undabot/index');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetGitHubApi() {
        $gitHubApi = "https://api.github.com/search/issues?q=php%20rocks+type:issue";
        $client = self::createClient();
        $client->request('GET', $gitHubApi);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetTermScoreNoBody() {
        $client = self::createClient();
        $client->request('POST', 'http://localhost:8000/undabot/getTermScore');

        $this->assertContainsOnlyInstancesOf(TermScore::class, $client->getResponse()->getContent());

    }


}