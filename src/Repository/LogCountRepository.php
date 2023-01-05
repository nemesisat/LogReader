<?php

declare(strict_types=1);

namespace App\Repository;
use App\Entity\LogCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LogCountRepository extends  ServiceEntityRepository
{
    /**
     * @method LogCount|null find($id, $lockMode = null, $lockVersion = null)
     * @method LogCount|null findOneBy(array $criteria, array $orderBy = null)
     * @method LogCount[]    findAll()
     * @method LogCount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogCount::class);
    }

    /**
     * @param string $service
     * @param \DateTimeInterface $timestamp
     * @param int $statusCode
     * @return void
     */
    public function save(string $service, \DateTimeInterface $timestamp, int $statusCode): void
    {
        $entity = new LogCount();
        $entity->setService($service);
        $entity->setTimestamp($timestamp);
        $entity->setStatusCode($statusCode);

        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string|null $serviceNames
     * @param int|null $statusCode
     * @param string|null $startDate
     * @param string|null $endDate
     * @return int
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countByFilter(
        ?string $serviceNames,
        ?int $statusCode,
        ?string $startDate,
        ?string $endDate
    ): int {
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->select('COUNT(s)');

        if ($serviceNames) {
            $queryBuilder->andWhere('s.service IN (:serviceNames)');
            $queryBuilder->setParameter('serviceNames', $serviceNames);
        }

        if ($statusCode) {
            $queryBuilder->andWhere('s.statusCode = :statusCode');
            $queryBuilder->setParameter('statusCode', $statusCode);
        }

        if ($startDate) {
            $queryBuilder->andWhere('s.timestamp >= :startDate');
            $queryBuilder->setParameter('startDate', new \DateTime($startDate));
        }

        if ($endDate) {
            $queryBuilder->andWhere('s.timestamp <= :endDate');
            $queryBuilder->setParameter('endDate', new \DateTime($endDate));
        }

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }

}
