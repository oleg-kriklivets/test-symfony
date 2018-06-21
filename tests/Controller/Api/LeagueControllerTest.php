<?php

namespace App\Test\Controller\Api;

use App\Entity\League;
use App\Tests\BaseTestCase;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueControllerTest extends BaseTestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testGetLeaguesList()
    {
        $league1 = new League();
        $league1->setName('testName1');

        $league2 = new League();
        $league2->setName('testName2');

        $this->entityManager->persist($league1);
        $this->entityManager->persist($league2);
        $this->entityManager->flush();
        $this->client->request('GET', '/api/leagues');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            [
                'id' => 1,
                'name' => 'testName1'
            ],
            [
                'id' => 2,
                'name' => 'testName2'
            ]
        ]), $this->client->getResponse()->getContent());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testDeleteLeague()
    {
        $league1 = new League();
        $league1->setName('testName1');

        $this->entityManager->persist($league1);
        $this->entityManager->flush();

        $league = $this->entityManager->getRepository(League::class)->findOneBy(['name' => 'testName1']);
        $this->assertNotNull($league);

        $this->client->request('DELETE', '/api/league/1');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $league = $this->entityManager->getRepository(League::class)->findOneBy(['name' => 'testName1']);
        $this->assertNull($league);
    }
}
