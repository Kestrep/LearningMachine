<?php

namespace App\Repository;

use App\Entity\Subcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Subcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subcategory[]    findAll()
 * @method Subcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcategoryRepository extends ServiceEntityRepository
{
    private $currentUser;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->currentUser = $security->getUser();
        parent::__construct($registry, Subcategory::class);
    }

    /**
     * @return Subcategory[] Returns an array of Subcategory owned by the current user
     */
    public function findAllFromCurrentUser() {
        $query = $this
                    ->createQueryBuilder('s')
                    ->join('s.category', 'cat')
                    ->join('cat.user', 'u')
                    ->where('u = :user')
                    ->setParameter('user', $this->currentUser)
                    // ->setMaxResults()
                    ->orderBy('s.name', 'DESC')
                    ->getQuery()
                    ->getResult()
                    ;
        return $query;
    }

    /**
     * @return Category Returns the last category from the current user
     */
    public function findAllFromGivenCategoryFromCurrentUser($category) {
        $query = $this
                    ->createQueryBuilder('s')
                    ->join('s.category', 'cat')
                    ->join('cat.user', 'u')
                    ->where('u = :user')
                    ->andWhere('cat = :category')
                    ->setParameter('user', $this->currentUser)
                    ->setParameter('category', $category)
                    // ->setMaxResults()
                    ->orderBy('s.updatedAt', 'DESC')
                    ->getQuery()
                    ->getResult()
                    ;
        return $query;
    }

    /**
     * @return Category Returns the last category from the current user
     */
    public function findLastSubcategoryFromGivenCategoryFromCurrentUser($category) {
        $query = $this
                    ->createQueryBuilder('s')
                    ->join('s.category', 'cat')
                    ->join('cat.user', 'u')
                    ->where('u = :user')
                    ->andWhere('cat = :category')
                    ->setParameter('user', $this->currentUser)
                    ->setParameter('category', $category)
                    ->setMaxResults(1)
                    ->orderBy('s.updatedAt', 'DESC')
                    ->getQuery()
                    ->getSingleResult()
                    ;
        return $query;
    }

    // /**
    //  * @return Subcategory[] Returns an array of Subcategory objects
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
    public function findOneBySomeField($value): ?Subcategory
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
