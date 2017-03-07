<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/6/2017 11:06 PM
 */

namespace App\Library\Requests\Account\Validation;


use App\Library\Requests\Account\Activate as ActivateRequest;
use App\Validation\Player\Email;
use App\Validation\Player\Password;
use Phalcon\Validation;
use Phalcon\Validation\Message\Group;

class Activate extends Validation
{
    /** @var Password */
    protected $_passwordValidation;

    /** @var Email */
    protected $_emailValidation;

    public function initialize()
    {
        $this->_passwordValidation = new Password();

        $this->_emailValidation = new Email();
    }

    /**
     * Executed after validation
     *
     * @param array $data
     * @param ActivateRequest $entity
     * @param Group $messages
     */
    public function afterValidation($data, ActivateRequest $entity, $messages)
    {
        /** @var Validation\Message[] $validationMessages */
        $validationMessages = $this->_passwordValidation->validate(null, $entity);
        $messages->appendMessages($validationMessages);

        $validationMessages = $this->_emailValidation->validate(null, $entity);
        $messages->appendMessages($validationMessages);
    }
}