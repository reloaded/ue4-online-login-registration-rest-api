<?php

namespace Reloaded\UnrealEngine4\Models;

abstract class AbstractPlayers extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     * @Primary
     * @Column(type="string", length=16, nullable=false)
     */
    protected $Id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $Email;

    /**
     *
     * @var string
     * @Column(type="string", length=25, nullable=false)
     */
    protected $InGameName;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    protected $Password;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $FirstName;

    /**
     *
     * @var string
     * @Column(type="string", length=50, nullable=false)
     */
    protected $LastName;

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
     * Method to set the value of field Email
     *
     * @param string $Email
     * @return $this
     */
    public function setEmail($Email)
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * Method to set the value of field InGameName
     *
     * @param string $InGameName
     * @return $this
     */
    public function setInGameName($InGameName)
    {
        $this->InGameName = $InGameName;

        return $this;
    }

    /**
     * Method to set the value of field Password
     *
     * @param string $Password
     * @return $this
     */
    public function setPassword($Password)
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * Method to set the value of field FirstName
     *
     * @param string $FirstName
     * @return $this
     */
    public function setFirstName($FirstName)
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    /**
     * Method to set the value of field LastName
     *
     * @param string $LastName
     * @return $this
     */
    public function setLastName($LastName)
    {
        $this->LastName = $LastName;

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
     * Returns the value of field Email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * Returns the value of field InGameName
     *
     * @return string
     */
    public function getInGameName()
    {
        return $this->InGameName;
    }

    /**
     * Returns the value of field Password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * Returns the value of field FirstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->FirstName;
    }

    /**
     * Returns the value of field LastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->LastName;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("ue4");
        $this->hasMany('Id', 'Reloaded\UnrealEngine4\Models\PlayerAccountRecovery', 'PlayerId', ['alias' => 'PlayerAccountRecovery']);
        $this->hasMany('Id', 'Reloaded\UnrealEngine4\Models\PlayerSecurityLog', 'PlayerId', ['alias' => 'PlayerSecurityLog']);
        $this->hasMany('Id', 'Reloaded\UnrealEngine4\Models\PlayerSessions', 'PlayerId', ['alias' => 'PlayerSessions']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractPlayers[]|AbstractPlayers
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AbstractPlayers
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
        return 'players';
    }

}
