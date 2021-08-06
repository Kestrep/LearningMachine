<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    private $user;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        $this->user = $security->getUser();
        parent::__construct($registry, Card::class);
    }

    /**
     * 
     */
    public function findUserCards($count = 20, $idList = [0 => 0])
    {
        if($idList === []) {
            $idList === [0 => 1];
        }
        $query = $this
                    ->createQueryBuilder('c')
                    ->join('c.subcategory', 's')
                    ->join('s.category', 'cat')
                    ->join('cat.user', 'u')
                    ->where('u = :user')
                    ->andWhere('c.id NOT IN (:idList)')
                    ->setParameter('user', $this->user)
                    ->setParameter('idList', $idList)
                    // ->setFirstResult(50)
                    ->setMaxResults($count)
                    ->orderBy('c.playAt', 'ASC')
                    ->getQuery()
                    ->getResult()
                    ;
        return $query;
    }

    /**
     * Je prends le User
     * Je prends les catégories qui m'intéressent (par défault : toutes)
     * Je prends pour chacune les sous-catégories qui m'intéressent (Par défault : toutes)
     * Je prends toutes les cards liées
     * Max-Résult : 10
     * 
     * Voir pour une pagination
     * 
     * Il faut donc que je parte du UserRepository
     */

    // /**
    //  * @return Card[] Returns an array of Card objects
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
    public function findOneBySomeField($value): ?Card
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
