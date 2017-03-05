<?php

namespace Reloaded\UnrealEngine4\Models;

abstract class AbstractPlayerSessions extends AbstractModel
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=16, nullable=false)
     */
    protected $PlayerId;

    /**
     *
     * @var string
     * @Column(type="string", length=16, nullable=false)
     */
    protected $SessionId;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $Expiration;

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
    protected $Created;

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
     * Method to set the value of field SessionId
     *
     * @param string $SessionId
     * @return $this
     */
    public function setSessionId($SessionId)
    {
        $this->SessionId = $SessionId;

        return $this;
    }

    /**
     * Method to set the value of field Expiration
     *
     * @param string $Expiration
     * @return $this
     */
    public function setExpiration($Expiration)
    {
        $this->Expiration = $Expiration;

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
     * Method to set the value of field Created
     *
     * @param string $Created
     * @return $this
     */
    public function setCreated($Created)
    {
        $this->Created = $Created;

        return $this;
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
     * Returns the value of field SessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->SessionId;
    }

    /**
     * Returns the value of field Expiration
     *
     * @return string
     */
    public function getExpiration()
    {
        return $this->Expiration;
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
     * Returns the value of field Created
     *
     * @return string
     */
    public function getCreated()
    {
        return $this->Created;
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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'player_sessions';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractPlayerSessions[]|AbstractPlayerSessions
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractPlayerSessions
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
