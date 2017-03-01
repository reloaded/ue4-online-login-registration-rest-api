<?php

namespace Reloaded\UnrealEngine4\Models;

class PlayerSecurityLog extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Column(type="string", length=16, nullable=false)
     */
    protected $Id;

    /**
     *
     * @var string
     * @Column(type="string", length=16, nullable=false)
     */
    protected $PlayerId;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $LogType;

    /**
     *
     * @var string
     * @Column(type="string", length=16, nullable=false)
     */
    protected $RemoteIp;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $DateTime;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    protected $Message;

    /**
     * Method to set the value of field Id
     *
     * @param string $Id
     * @return $this
     */
    public function setId($Id)
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Method to set the value of field PlayerId
     *
     * @param string $PlayerId
     * @return $this
     */
    public function setPlayerId($PlayerId)
    {
        $this->PlayerId = $PlayerId;

        return $this;
    }

    /**
     * Method to set the value of field LogType
     *
     * @param string $LogType
     * @return $this
     */
    public function setLogType($LogType)
    {
        $this->LogType = $LogType;

        return $this;
    }

    /**
     * Method to set the value of field RemoteIp
     *
     * @param string $RemoteIp
     * @return $this
     */
    public function setRemoteIp($RemoteIp)
    {
        $this->RemoteIp = $RemoteIp;

        return $this;
    }

    /**
     * Method to set the value of field DateTime
     *
     * @param string $DateTime
     * @return $this
     */
    public function setDateTime($DateTime)
    {
        $this->DateTime = $DateTime;

        return $this;
    }

    /**
     * Method to set the value of field Message
     *
     * @param string $Message
     * @return $this
     */
    public function setMessage($Message)
    {
        $this->Message = $Message;

        return $this;
    }

    /**
     * Returns the value of field Id
     *
     * @return string
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Returns the value of field PlayerId
     *
     * @return string
     */
    public function getPlayerId()
    {
        return $this->PlayerId;
    }

    /**
     * Returns the value of field LogType
     *
     * @return string
     */
    public function getLogType()
    {
        return $this->LogType;
    }

    /**
     * Returns the value of field RemoteIp
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->RemoteIp;
    }

    /**
     * Returns the value of field DateTime
     *
     * @return string
     */
    public function getDateTime()
    {
        return $this->DateTime;
    }

    /**
     * Returns the value of field Message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->Message;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("ue4");
        $this->belongsTo('PlayerId', 'Reloaded\UnrealEngine4\Models\\Players', 'Id', ['alias' => 'Players']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PlayerSecurityLog[]|PlayerSecurityLog
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PlayerSecurityLog
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'player_security_log';
    }

}
