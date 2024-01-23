<?php
/*
 * Таблица "Образование"
 * 
 * (c) Алексей Третьяков <ralfzeit@gmail.com>
 * 
 */

namespace App\Repository;

use App\Entity\Education;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Education>
 *
 * @method Education|null find($id, $lockMode = null, $lockVersion = null)
 * @method Education|null findOneBy(array $criteria, array $orderBy = null)
 * @method Education[]    findAll()
 * @method Education[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EducationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Education::class);
    }
}
