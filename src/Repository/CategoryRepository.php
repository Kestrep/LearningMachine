<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    private $currentUser;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->currentUser = $security->getUser();
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[] Returns an array of Subcategory owned by the current user
     */
    public function findAllFromCurrentUser() {
        $query = $this
                    ->createQueryBuilder('c')
                    ->join('c.user', 'u')
                    ->where('u = :user')
                    ->setParameter('user', $this->currentUser)
                    // ->setMaxResults(5)
                    ->orderBy('c.updatedAt', 'DESC')
                    ->getQuery()
                    ->getResult()
                    ;
        return $query;
    }
    /**
     * @return Category Returns the last category from the current user
     */
    public function findLastCategoryFromUser() {
        $query = $this
                    ->createQueryBuilder('c')
                    ->join('c.user', 'u')
                    ->where('u = :user')
                    ->setParameter('user', $this->currentUser)
                    ->setMaxResults(1)
                    ->orderBy('c.updatedAt', 'DESC')
                    ->getQuery()
                    ->getSingleResult()
                    ;
        return $query;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
