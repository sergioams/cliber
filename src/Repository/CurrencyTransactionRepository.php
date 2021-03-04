<?php
namespace App\Repository;

use App\Entity\CurrencyTransactionEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CurrencyTransactionEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyTransactionEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyTransactionEntity[]    findAll()
 * @method CurrencyTransactionEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyTransactionRepository extends ServiceEntityRepository{
    public function __construct(ManagerRegistry $registry){
        parent::__construct($registry, CurrencyTransactionEntity::class);
    }

    // /**
    //  * @return CurrencyTransactionEntity[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CurrencyTransactionEntity
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
