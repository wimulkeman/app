<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageTest extends WebTestCase
{
    public function testSimple(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseStatusCodeSame(200);
        self::assertStringContainsString('Symfony Application', $crawler->text());
    }
}
