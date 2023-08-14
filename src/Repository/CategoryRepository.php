<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findAllOrdered()
    {
//        $dql = 'SELECT cat FROM App\Entity\Category cat ORDER BY cat.name DESC';

//        $query = $this->getEntityManager()->createQuery($dql);

        $qb = $this->createQueryBuilder('cat')
            ->addOrderBy('cat.name', 'DESC');

        $query = $qb->getQuery();


        return $query->execute();
    }

    public function buscar($texto)
    {

//        Se  usa LowerCase porque Postgres no es CI
        return $this->createQueryBuilder('category')
            ->leftJoin('category.fortuneCookies', 'fc')
            ->andWhere('LOWER(category.name) LIKE :textoABuscar 
                        OR category.iconKey LIKE :textoABuscar 
                        OR fc.fortune LIKE :textoABuscar')

            ->setParameter('textoABuscar', '%' . strtolower($texto) . '%')
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
