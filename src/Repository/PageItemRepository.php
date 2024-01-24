<?php

namespace App\Repository;

use App\Entity\PageItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PageItem>
 *
 * @method PageItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageItem[]    findAll()
 * @method PageItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PageItem::class);
    }

    public function SearchInDirectoryCode($input): array
    {
        $qb = $this->createQueryBuilder('item');

        $qb->andWhere('item.code LIKE :code')
            ->setParameter('code', '%' . $input . '%');
        return $this->getItemsWithChildren($qb->getQuery()->getResult());

    }

    public function SearchInDirectoryName($input): array
    {
        $qb = $this->createQueryBuilder('item');

        $qb->andWhere('LOWER(item.name) LIKE LOWER(:name)')
            ->setParameter('name', '%' . $input . '%');
        return $this->getItemsWithChildren($qb->getQuery()->getResult());

    }

    private function getItemsWithChildren($items): array
    {
        $result = [];

        foreach ($items as $item) {
            $children = $this->findBy(['parent_code' => $item->getCode()]);
            $item->children = $this->getItemsWithChildren($children);

            $result[] = $item;
        }
        return $result;
    }

//    /**
//     * @return PageItem[] Returns an array of PageItem objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PageItem
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
