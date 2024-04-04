<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
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

        $qb = $this->createQueryBuilder('category')
            ->addOrderBy('category.name', 'DESC');
        $this->addFortuneCookieAndSelect($qb);

        $query = $qb->getQuery();


        return $query->execute();
    }

    /**
     * @param QueryBuilder $qb
     * @return QueryBuilder
     */
    private function addFortuneCookieAndSelect(QueryBuilder $qb): QueryBuilder
    {
       return $qb->leftJoin('category.fortuneCookies', 'fc')
            ->addSelect('fc');

    }

    public function buscar($texto)
    {

//        Se  usa LowerCase porque Postgres no es CI
        $qb = $this->createQueryBuilder('category');

        $this->addFortuneCookieAndSelect($qb);

        $qb
            ->andWhere('LOWER(category.name) LIKE :textoABuscar 
                        OR category.iconKey LIKE :textoABuscar 
                        OR fc.fortune LIKE :textoABuscar')
            ->setParameter('textoABuscar', '%' . strtolower($texto) . '%');
        $query = $qb
            ->getQuery();

        return $query->execute();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findWithFortunesJoin(string $id)
    {
        $qb = $this->createQueryBuilder('category')
            ->andWhere('category.id = :id')
            ->setParameter('id', $id);
        $this->addFortuneCookieAndSelect($qb);
       return $qb->getQuery()
            ->getOneOrNullResult()
        ;


    }
}
