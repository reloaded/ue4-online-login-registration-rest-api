<?php

namespace App\Models;

abstract class AbstractPlayerAccountRecovery extends AbstractModel
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
     * @Column(type="string", length=10, nullable=false)
     */
    protected $Code;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $Expiration;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $GeneratedOn;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    protected $Type;

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
     * Method to set the value of field Code
     *
     * @param string $Code
     * @return $this
     */
    public function setCode($Code)
    {
        $this->Code = $Code;

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
     * Method to set the value of field GeneratedOn
     *
     * @param string $GeneratedOn
     * @return $this
     */
    public function setGeneratedOn($GeneratedOn)
    {
        $this->GeneratedOn = $GeneratedOn;

        return $this;
    }

    /**
     * Method to set the value of field Type
     *
     * @param string $Type
     * @return $this
     */
    public function setType($Type)
    {
        $this->Type = $Type;

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
     * Returns the value of field Code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->Code;
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
     * Returns the value of field GeneratedOn
     *
     * @return string
     */
    public function getGeneratedOn()
    {
        return $this->GeneratedOn;
    }

    /**
     * Returns the value of field Type
     *
     * @return string
     */
    public function getType()
    {
        return $this->Type;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("ue4");
        $this->belongsTo('PlayerId', 'App\Models\\Players', 'Id', ['alias' => 'Players']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'player_account_recovery';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractPlayerAccountRecovery[]|AbstractPlayerAccountRecovery
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractPlayerAccountRecovery
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
