<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/4/2017 3:01 PM
 */

namespace App\Models\Validation;


use App\Validation\Player\InGameName;
use App\Validation\Player\Email;
use App\Validation\Player\PersonalName;
use Phalcon\Validation;
use Phalcon\Validation\Message\Group;

class Players extends Validation
{
    /** @var Email */
    protected $emailValidation;

    /** @var InGameName */
    protected $inGameNameValidation;

    /** @var PersonalName */
    protected $personalNameValidation;

    public function initialize()
    {
        $this->emailValidation = new Email();

        $this->inGameNameValidation = new InGameName();

        $this->personalNameValidation = new PersonalName();
    }

    /**
     * Executed after validation
     *
     * @param array $data
     * @param object $entity
     * @param Group $messages
     */
    public function afterValidation($data, $entity, $messages)
    {
        /** @var Validation\Message[] $validationMessages */
        $validationMessages = $this->emailValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);

        $validationMessages = $this->inGameNameValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);

        $validationMessages = $this->personalNameValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);
    }
}