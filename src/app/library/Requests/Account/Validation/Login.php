<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/4/2017 5:21 PM
 */

namespace App\Library\Requests\Account\Validation;


use App\Library\Requests\Account\Login as LoginRequest;
use App\Validation\Player\Email;
use App\Validation\Player\Password;
use Phalcon\Validation;
use Phalcon\Validation\Message\Group;

class Login extends Validation
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
     * @param LoginRequest $entity
     * @param Group $messages
     */
    public function afterValidation($data, LoginRequest $entity, $messages)
    {
        /** @var Validation\Message[] $validationMessages */
        $validationMessages = $this->_passwordValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);

        $validationMessages = $this->_emailValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);
    }
}