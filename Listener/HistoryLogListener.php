<?php
namespace Cf\HistoryLogBundle\Listener;

use Cf\HistoryLogBundle\Entity\CfHistoryLog;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Inflector;

/**
 * Class HistoryLogListener
 *
 * @package cf\HistoryLogBundle\Listener
 */
class HistoryLogListener implements EventSubscriber
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
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'postPersist',
            'preUpdate',
            'postUpdate',
            'preRemove',
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist( LifecycleEventArgs $args )
    {
        if (method_exists( $args->getEntity(), 'getParametersHistoryLog' )) {
            $this->createLog( $args, 'create' );
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate( LifecycleEventArgs $args )
    {
        if (method_exists( $args->getEntity(), 'getParametersHistoryLog' )) {
            $this->createLog( $args, 'update' );
        }

    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate( LifecycleEventArgs $args )
    {
        $em = $this->container->get( 'doctrine.orm.entity_manager' );
        $em->flush();
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preRemove( LifecycleEventArgs $args )
    {
        if (method_exists( $args->getEntity(), 'getParametersHistoryLog' )) {
            $this->createLog( $args, 'remove' );
        }
    }

    /**
     *
     * @param $action
     */
    public function createLog( $args, $action )
    {
        # Entity manager
        //        $em     = $args->getEntityManager();
        $em     = $this->container->get( 'doctrine.orm.entity_manager' );
        $uow    = $em->getUnitOfWork();
        $entity = $args->getEntity();

        foreach ($entity->getParametersHistoryLog() as $field_name) {
            if (is_string( $field_name )) {
                $method = sprintf( 'get%s', \Doctrine\Common\Util\Inflector::camelize( $field_name ) );
                if (isset( $entity ) && method_exists( $entity, $method )) {
                    // Here get all parameters/field to save inside HistoryLog

                    switch ($action) {
                        case 'create':
                            if ($entity->$method() !== null) {
                                $old_value = '';
                                if (( $entity->$method() instanceof \DateTime )) {
                                    $new_value = $entity->$method()->format( 'Y-m-d H:i:s' );
                                } elseif (is_object( $entity->$method() ) && method_exists( $entity->$method(), 'getId' )) {
                                    $new_value = $entity->$method()->getId();
                                } elseif (is_string( $entity->$method() ) || is_numeric( $entity->$method() )) {
                                    $new_value = $entity->$method();
                                } else {
                                    goto CONTINUE_GOTO;
                                }
                                $section = $args->getEntity()->getId();
                            } else {
                                goto CONTINUE_GOTO;
                            }
                            break;
                        case 'update':
                            $eventArgs            = $args;
                            $camelized_field_name = \Doctrine\Common\Util\Inflector::camelize( $field_name );
                            if ($eventArgs->hasChangedField( $camelized_field_name )) {
                                $old_value = $args->getOldValue( $camelized_field_name );
                                $new_value = $args->getNewValue( $camelized_field_name );
                                if ($old_value != $new_value) {
                                    if ( $old_value instanceof \DateTime ) {
                                        $old_value = $old_value->format( 'Y-m-d H:i:s' );
                                    } elseif (is_object( $old_value ) && method_exists( $old_value, 'getId' )) {
                                        $old_value = $old_value->getId();
                                    } elseif (is_string( $old_value ) || is_numeric( $old_value )) {
                                        $old_value = $old_value;
                                    } else {
                                        goto CONTINUE_GOTO;
                                    }

                                    if ( $new_value instanceof \DateTime ) {
                                        $new_value = $new_value->format( 'Y-m-d H:i:s' );
                                    } elseif (is_object( $new_value ) && method_exists( $new_value, 'getId' )) {
                                        $new_value = $new_value->getId();
                                    } elseif (is_string( $new_value ) || is_numeric( $new_value )) {
                                        $new_value = $new_value;
                                    } else {
                                        goto CONTINUE_GOTO;
                                    }

                                    $section = $args->getEntity()->getId();
                                }else{
                                    goto CONTINUE_GOTO;
                                }
                            } else {
                                goto CONTINUE_GOTO;
                            }
                            break;
                        case 'remove':
                            if ($entity->$method() !== null) {
                                if (( $entity->$method() instanceof \DateTime )) {
                                    $old_value = $entity->$method()->format( 'Y-m-d H:i:s' );
                                } elseif (is_object( $entity->$method() ) && method_exists( $entity->$method(), 'getId' )) {
                                    $old_value = $entity->$method()->getId();
                                } elseif (is_string( $entity->$method() ) || is_numeric( $entity->$method() )) {
                                    $old_value = $entity->$method();
                                } else {
                                    goto CONTINUE_GOTO;
                                }
                                $new_value = '';
                                $section   = $args->getEntity()->getId();
                            } else {
                                goto CONTINUE_GOTO;
                            }
                            break;
                    }
                    global $josecarlos;
                    $history_log_args   = [
                        'entity'         => get_class( $entity ),
                        'section'        => $section,/* */
                        'action'         => $action,
                        'level'          => 'success',
                        'user_id'        => $this->_getUser() !== null ? $this->_getUser()->getId() : '-1',
                        'username'       => $this->_getUser() !== null ? $this->_getUser()->getUsername() : 'anonymous',
                        'user_ip_source' => $this->_getRequest() !== null ? $this->_getRequest()->getClientIp() : 'unknown',
                        'field_name'     => $field_name,
                        'old_value'      => $old_value,
                        'new_value'      => $new_value,
                        'msg'            => 'YYY',
                    ];
                    $new_cf_history_log = new CfHistoryLog( $history_log_args );
                    $em->persist( $new_cf_history_log );


                }
            }
            CONTINUE_GOTO:
        }
        if ($action !== 'update') {
            $em->flush();
        }
    }

    private function _getUser()
    {
        if ($this->container->has( 'security.context' ) === false) {
            throw new \LogicException( 'The SecurityBundle is not registered in your application.' );
        }
        if (null === $token = $this->container->get( 'security.context' )->getToken()) {
            return null;
        }
        if ( ! is_object( $user = $token->getUser() )) {
            return null;
        }

        return $user;
    }

    private function _getRequest()
    {
        return $this->container->get( 'request' );
    }
}