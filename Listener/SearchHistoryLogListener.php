<?php
namespace Cf\HistoryLogBundle\Listener;

use Cf\HistoryLogBundle\Entity\CfHistoryLog;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Inflector;

/**
 * Class SearchHistoryLogListener
 *
 * Search intro History DB
 *
 * @package cf\HistoryLogBundle\Listener
 */
class SearchHistoryLogListener
{
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct( ContainerInterface $container )
    {
        $this->container = $container;
    }

    /**
     * @param array $params
     * @param $count
     *
     * @return array
     */
    public function getSearchHistoryByEntityAndSection( array $params, &$count )
    {

        //TODO: Cambiar todo este codigo por search communRepository.
        extract( $params );
        $em = $this->container->get( 'doctrine.orm.entity_manager' );
        //        $expr = $em->getExpressionBuilder();

        //get Count
        $qb_count = $em->createQueryBuilder();
        $qb_count->select( 'COUNT(entity.id)' )->from( 'cfHistoryLogBundle:CfHistoryLog', 'entity' );
        $qb_count = $qb_count->andWhere( 'entity.'.'entity'.' LIKE '.':entity' );
        $qb_count = $qb_count->andWhere( 'entity.'.'section'.' = '.':section' );
        $qb_count->setParameter( 'entity', '%'.$entity.'%' );
        $qb_count->setParameter( 'section', $section );
        $count = $qb_count->getQuery()->getSingleScalarResult();

        $qb = $em->createQueryBuilder();
        $qb->select( 'entity' )->from( 'cfHistoryLogBundle:CfHistoryLog', 'entity' );
        $qb = $qb->andWhere( 'entity.'.'entity'.' LIKE '.':entity' );
        $qb = $qb->andWhere( 'entity.'.'section'.' = '.':section' );
        $qb->setParameter( 'entity', '%'.$entity.'%' );
        $qb->setParameter( 'section', $section );

        if (isset( $limit ) && is_numeric( $limit )) {
            $qb->setMaxResults( $limit );
        }
        if (isset( $offset ) && is_numeric( $limit )) {
            $qb->setFirstResult( $offset );
        }

        return $qb->getQuery()->getResult();

    }

    private function _getRequest()
    {
        return $this->container->get( 'request' );
    }
}