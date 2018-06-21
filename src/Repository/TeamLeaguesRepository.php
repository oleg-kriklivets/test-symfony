<?php

namespace App\Repository;

use App\Entity\TeamLeagues;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeamLeagues|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamLeagues|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamLeagues[]    findAll()
 * @method TeamLeagues[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamLeaguesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeamLeagues::class);
    }
}
