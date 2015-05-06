<?php
namespace Cf\HistoryLogBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CfHistoryLogRepository extends EntityRepository
{

    /**
     * @param $entity
     * @param $section
     * @param $limit
     * @param $offset
     *
     * @return array
     */
    public function findAllByEntityAndSection( $entity, $section, $limit, $offset )
    {
        $em   = $this->getEntityManager();
        $expr = $em->getExpressionBuilder();

        $qb = $em->createQueryBuilder();
        $qb->select( 'entity' )->from( $this->getEntityName(), 'entity' );
        $qb = $qb->andWhere( 'entity.'.'entity'.' = '.':entity' );
        $qb = $qb->andWhere( 'entity.'.'section'.' = '.':section' );
        $qb->setParameter( 'entity', $entity );
        $qb->setParameter( 'section', $section );

        if (isset( $limit ) && is_numeric( $limit )) {
            $qb->setMaxResults( $limit );
        }
        if (isset( $offset ) && is_numeric( $limit )) {
            $qb->setFirstResult( $offset );
        }

        return $qb->getQuery()->getResult();
    }
}