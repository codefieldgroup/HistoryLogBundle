<?php

namespace Cf\HistoryLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cf History Log
 * @ORM\Entity
 * @ORM\Table(name="cf_history_log")
 * @ORM\Entity(repositoryClass="cf\HistoryLogBundle\Entity\CfHistoryLogRepository")
 */
class CfHistoryLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=100, nullable=true)
     */
    private $entity;

    /**
     * @var string
     *
     * @ORM\Column(name="section", type="string", length=100, nullable=true)
     */
    private $section;

    /**
     * @var string
     *
     * @ORM\Column(name="level", type="string", length=100, nullable=true)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     */
    private $username;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="user_ip_source", type="string", length=255, nullable=false)
     */
    private $userIpSource;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=100, nullable=true)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=100, nullable=true)
     */
    private $fieldName;

    /**
     * @var string
     *
     * @ORM\Column(name="old_value", type="text", nullable=true)
     */
    private $oldValue;

    /**
     * @var string
     *
     * @ORM\Column(name="new_value", type="text", nullable=true)
     */
    private $newValue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=false)
     */
    private $datetime;

    /**
     * @var string
     *
     * @ORM\Column(name="msg", type="text", nullable=true)
     */
    private $msg;

    /**
     * Constructor for History
     *
     * @param $args
     */
    public function __construct( $args )
    {
        //TODO: JCRC: Coger todos los parametros y crear el history.
        extract( $args );

        $this->setEntity( $entity );
        $this->setSection( $section );
        $this->setAction( $action );
        $this->setLevel( $level );
        $this->setUserId( $user_id );
        $this->setUsername( $username );
        $this->setUserIpSource( $user_ip_source );
        $this->setFieldName( $field_name );
        $this->setOldValue( $old_value );
        $this->setNewValue( $new_value );
        $this->setMsg( $msg );
        $this->setDatetime( new \DateTime() );
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set section
     *
     * @param string $section
     *
     * @return History
     */
    public function setSection( $section )
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return string
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return History
     */
    public function setEntity( $entity )
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return History
     */
    public function setUsername( $username )
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return History
     */
    public function setUserId( $userId )
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set userIpSource
     *
     * @param string $userIpSource
     *
     * @return History
     */
    public function setUserIpSource( $userIpSource )
    {
        $this->userIpSource = $userIpSource;

        return $this;
    }

    /**
     * Get userIpSource
     *
     * @return string
     */
    public function getUserIpSource()
    {
        return $this->userIpSource;
    }

    /**
     * Set action
     *
     * @param string $action
     *
     * @return History
     */
    public function setAction( $action )
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $level
     */
    public function setLevel( $level )
    {
        $this->level = $level;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName( $fieldName )
    {
        $this->fieldName = $fieldName;
    }

    /**
     * Set oldValue
     *
     * @param string $oldValue
     *
     * @return History
     */
    public function setOldValue( $oldValue )
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    /**
     * Get oldValue
     *
     * @return string
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * Set newValue
     *
     * @param string $newValue
     *
     * @return History
     */
    public function setNewValue( $newValue )
    {
        $this->newValue = $newValue;

        return $this;
    }

    /**
     * Get newValue
     *
     * @return string
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return History
     */
    public function setDatetime( $datetime )
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return History
     */
    public function setMsg( $msg )
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }
}
