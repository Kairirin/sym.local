<?php

namespace App\Repository;

use App\Entity\Imagen;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Imagen>
 */
class ImagenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imagen::class);
    }

    public function remove(Imagen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Imagen[] Returns an array of Imagen objects
     */
    public function findImagenes(string $descripcion, string $fechaInicial, $fechaFinal): array
    {
        $qb = $this->createQueryBuilder('i');
        if (!is_null($descripcion) && $descripcion !== '') {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('i.descripcion', ':val'),
                    $qb->expr()->like('i.nombre', ':val')
                )
            )
                ->setParameter('val', '%' . $descripcion . '%');
        }
        if (!is_null($fechaInicial) && $fechaInicial !== '') {
            $dtFechaInicial = DateTime::createFromFormat('Y-m-d', $fechaInicial);
            $qb->andWhere($qb->expr()->gte('i.fecha', ':fechaInicial'))
                ->setParameter('fechaInicial', $dtFechaInicial);
        }
        if (!is_null($fechaFinal) && $fechaFinal !== '') {
            $dtFechaFinal = DateTime::createFromFormat('Y-m-d', $fechaFinal);
            $qb->andWhere($qb->expr()->lte('i.fecha', ':fechaFinal'))
                ->setParameter('fechaFinal', $dtFechaFinal);
        }
        return $qb->getQuery()->getResult();
    }

    public function findImagenesConCategoria(string $ordenacion, string $tipoOrdenacion)
    {
        $qb = $this->createQueryBuilder('imagen');
        $qb->addSelect('categoria')
            ->innerJoin('imagen.categoria', 'categoria')
            ->orderBy('imagen.' . $ordenacion, $tipoOrdenacion);
        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Imagen[] Returns an array of Imagen objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Imagen
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
