<?php

namespace App\Test\Controller\Api;

use App\Entity\League;
use App\Entity\Team;
use App\Tests\BaseTestCase;
use Doctrine\Common\Persistence\ObjectManager;

class TeamControllerTest extends BaseTestCase
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testGetTeamsList()
    {
        $league = new League();
        $league->setName('testName');

        $team1 = new Team();
        $team1->setName('testName1');
        $team1->setStrip('testStrip1');

        $team2 = new Team();
        $team2->setName('testName2');
        $team2->setStrip('testStrip2');
        $team2->setLeague($league);

        $this->entityManager->persist($league);
        $this->entityManager->persist($team1);
        $this->entityManager->persist($team2);
        $this->entityManager->flush();
        $this->client->request('GET', '/api/teams');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            [
                'id' => 2,
                'name' => 'testName2',
                'strip' => 'testStrip2',
                'league' => [
                    'id' => 1,
                    'name' => 'testName',
                ]
            ],
            [
                'id' => 1,
                'name' => 'testName1',
                'strip' => 'testStrip1',
                'league' => null
            ]
        ]), $this->client->getResponse()->getContent());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testGetLeagueTeamsList()
    {
        $league = new League();
        $league->setName('testName');

        $team1 = new Team();
        $team1->setName('testName1');
        $team1->setStrip('testStrip1');
        $team1->setLeague($league);


        $team2 = new Team();
        $team2->setName('testName2');
        $team2->setStrip('testStrip2');
        $team2->setLeague($league);

        $team3 = new Team();
        $team3->setName('testName3');
        $team3->setStrip('testStrip3');

        $this->entityManager->persist($league);
        $this->entityManager->persist($team1);
        $this->entityManager->persist($team2);
        $this->entityManager->persist($team3);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/league/' . $league->getId() . '/teams');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertJsonStringEqualsJsonString(json_encode([
            [
                'id' => 1,
                'name' => 'testName1',
                'strip' => 'testStrip1',
            ],
            [
                'id' => 2,
                'name' => 'testName2',
                'strip' => 'testStrip2',
            ]
        ]), $this->client->getResponse()->getContent());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCreateTeam()
    {
        $league = new League();
        $league->setName('testName');
        $this->entityManager->persist($league);
        $this->entityManager->flush();

        $this->client->request('POST', '/api/team', [], [], [], json_encode([
            'name' => 'Arsenal',
            'strip' => 'yellow',
            'league' => ['id' => $league->getId()],
        ]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $team = $this->entityManager->getRepository(Team::class)->findOneBy(['name' => 'Arsenal']);
        $this->assertEquals('yellow', $team->getStrip());
        $this->assertEquals($league->getId(), $team->getLeague()->getId());
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testUpdateTeam()
    {
        $league = new League();
        $league->setName('testName');
        $this->entityManager->persist($league);
        $this->entityManager->flush();

        $this->client->request('POST', '/api/team/2222222', [], [], [], json_encode([
            'name' => 'Arsenal',
            'strip' => 'yellow',
            'league' => ['id' => $league->getId()],
        ]));
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());

        $team1 = new Team();
        $team1->setName('testName1');
        $team1->setStrip('testStrip1');
        $team1->setLeague($league);
        $this->entityManager->persist($team1);
        $this->entityManager->flush();


        $this->client->request('POST', '/api/team/' . $team1->getId(), [], [], [], json_encode([
            'name' => 'Arsenal-changed',
            'strip' => 'yellow-changed',
            'league' => null,
        ]));

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $team = $this->entityManager->getRepository(Team::class)->findOneBy(['name' => 'Arsenal-changed']);
        $this->assertEquals('yellow-changed', $team->getStrip());
        $this->assertEquals(null, $team->getLeague());
    }
}
