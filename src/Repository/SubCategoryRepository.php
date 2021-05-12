<?php

namespace App\Repository;

use App\Entity\SubCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method SubCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubCategory[]    findAll()
 * @method SubCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubCategoryRepository extends ServiceEntityRepository
{
    private $currentUser;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->currentUser = $security->getUser();
        parent::__construct($registry, SubCategory::class);
    }

    /**
     * @return SubCategory[] Returns an array of SubCategory owned by the current user
     */
    public function findAllFromCurrentUser() {
        $query = $this
                    ->createQueryBuilder('s')
                    ->join('s.category', 'cat')
                    ->join('cat.user', 'u')
                    ->where('u = :user')
                    ->setParameter('user', $this->currentUser)
                    ->setMaxResults(2)
                    ->orderBy('s.name', 'DESC')
                    ->getQuery()
                    ->getResult()
                    ;
        return $query;
    }

    // /**
    //  * @return SubCategory[] Returns an array of SubCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SubCategory
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
