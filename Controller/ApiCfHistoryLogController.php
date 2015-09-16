<?php

namespace Cf\HistoryLogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Doctrine\DBAL\DBALException as DBALException;

/**
 * CfHistoryLog controller.
 *
 * @RouteResource("history-log")
 */
class ApiCfHistoryLogController extends FOSRestController
{

    /**
     * @var
     */
    public $parameter;

    /**
     * @var
     */
    private $role_name;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->role_name = 'ROLE_HISTORY_LOG_';

        $this->parameter = [ 'length_row' => '10', 'count' => -1 ];
    }

    /**
     * Lists all Indications Templates entities.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function cgetAction( Request $request )
    {
        $utils = $this->get('cf.sclinicbundle.utils');
        /* Checking security access */
        $utils->checkSecurity($this->role_name.'LIST');
        try{
            $em       = $this->getDoctrine()->getManager();
            $entities = [ ];
            $code     = $request->query->has( 'code' ) ? $request->query->get( 'code' ) : null;
            switch ($code) {
                case 'list' :
                    // This section meaby go to the only get.
                    if ($this->has( 'cf.historylogbundle.search' ) && $request->query->has( 'section_id' )) {
                        $limit      = $request->query->has( 'limit' ) ? $request->query->get( 'limit' ) : 10;
                        $offset     = $request->query->has( 'offset' ) ? $request->query->get( 'offset' ) : 0;
                        $section_id = $request->query->has( 'section_id' ) ? $request->query->get( 'section_id' ) : null;
                        $entity     = $request->query->has( 'class_name_entity' ) ? $request->query->get(
                            'class_name_entity'
                        ) : null;
                        $section    = $section_id;
                        $params     = compact( 'entity', 'section', 'limit', 'offset' );
                        $entities   = $this->get( 'cf.historylogbundle.search' )->getSearchHistoryByEntityAndSection(
                            $params,
                            $this->parameter['count']
                        );
                    } else {
                        $entities = null;
                    }
                break;
                default:
                    break;
            }
            if ($entities !== null) {
                return $this->get( 'cf.commonbundle.restapi' )->buildRestApi(
                    $entities,
                    [ ],
                    [ 'parameter' => $this->parameter ]
                );
            }
        }catch ( DBALException $e ){
            return $this->get( 'cf.commonbundle.restapi' )->buildRestApi(
                null,
                $this->get( 'cf.commonbundle.messenger' )->getError( 500 )/*@Error DB Error*/
            );
        }catch ( \Exception $e ){
            return $this->get( 'cf.commonbundle.restapi' )->buildRestApi(
                null,
                $this->get( 'cf.commonbundle.messenger' )->getError( 501 )/*@Error Undefined*/,
                [ 'parameter' => $this->parameter ]
            );
        }
    }

}
