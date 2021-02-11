<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Service\UserManager;
use App\Tests\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCanShowPublicProfile()
    {
        $client = $this->createClient();
        $client->loginUser($this->getUserByUsername('testUser'));

        $entry = $this->getEntryByTitle('treść1');
        $entry = $this->getEntryByTitle('treść2');

        $crawler = $client->request('GET', '/u/regularUser');

        $this->assertCount(2, $crawler->filter('.kbin-entry-list-item'));
    }

    public function testUserCanFollow()
    {
        $client = $this->createClient();
        $manager = self::$container->get(UserManager::class);

        $client->loginUser($user = $this->getUserByUsername('regularUser'));

        $user2 = $this->getUserByUsername('regularUser2');
        $user3 = $this->getUserByUsername('regularUser3');
        $user4 = $this->getUserByUsername('regularUser4');

        $magazine  = $this->getMagazineByName('polityka', $user2);
        $magazine2  = $this->getMagazineByName('kuchnia', $user2);

        $this->getEntryByTitle('treść 1', null, null, $magazine, $user2);
        $this->getEntryByTitle('treść 2', null, null, $magazine2, $user2);
        $this->getEntryByTitle('treść 3', null, null, $magazine, $user3);
        $this->getEntryByTitle('treść 4', null, null, $magazine2, $user3);
        $this->getEntryByTitle('treść 5', null, null, $magazine, $user4);
        $this->getEntryByTitle('treść 6', null, null, $magazine2, $user4);

        $manager->follow($user3, $user2);

        $crawler = $client->request('GET', '/u/regularUser2');

        $this->assertSelectorTextContains('.kbin-entry-info-user .kbin-follow', '1');

        $client->submit(
            $crawler->filter('.kbin-entry-info-user .kbin-follow button')->selectButton('obserwuj')->form()
        );

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('.kbin-entry-info-user .kbin-follow', '2');

        $crawler = $client->request('GET', '/sub');

        $this->assertSelectorTextContains('.kbin-entry-title', 'treść 2');
        $this->assertCount(2, $crawler->filter('.kbin-entry-title'));

        $crawler = $client->request('GET', '/u/regularUser2');

        $client->submit(
            $crawler->filter('.kbin-entry-info-user .kbin-follow button')->selectButton('obserwujesz')->form()
        );

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('.kbin-entry-info-user .kbin-follow', '1');
    }
}
