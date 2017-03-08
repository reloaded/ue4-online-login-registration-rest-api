<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 12:49 AM
 */

namespace App\Library\Requests\Account\Validation;


use App\Library\Requests\Account\RecoverPassword as RecoverPasswordRequest;
use App\Validation\Player\Email;
use Phalcon\Validation;
use Phalcon\Validation\Message\Group;

class RecoverPassword extends Validation
{
    /** @var Email */
    protected $_emailValidation;

    public function initialize()
    {
        $this->_emailValidation = new Email();
    }

    /**
     * Executed after validation
     *
     * @param array $data
     * @param RecoverPasswordRequest $entity
     * @param Group $messages
     */
    public function afterValidation($data, RecoverPasswordRequest $entity, $messages)
    {
        /** @var Validation\Message[] $validationMessages */
        $validationMessages = $this->_emailValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);
    }
}