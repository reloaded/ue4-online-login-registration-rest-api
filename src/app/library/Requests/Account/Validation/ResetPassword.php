<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 1:34 AM
 */

namespace App\Library\Requests\Account\Validation;


use App\Library\Requests\Account\ResetPassword as ResetPasswordRequest;
use App\Validation\Player\Email;
use App\Validation\Player\Password;
use App\Validation\PlayerAccountRecovery\Code;
use Phalcon\Validation;
use Phalcon\Validation\Message\Group;
use Phalcon\Validation\Validator\Confirmation;

class ResetPassword extends Validation
{
    /** @var Password */
    protected $_passwordValidation;

    /** @var Email */
    protected $_emailValidation;

    /** @var Code */
    protected $_codeValidation;

    public function initialize()
    {
        $this->_passwordValidation = new Password();

        $this->_emailValidation = new Email();

        $this->_codeValidation = new Code();

        $this->add(
            'Password',
            new Confirmation([
                'with' => 'PasswordConfirm',
                'message' => 'Passwords do not match.'
            ])
        );
    }

    /**
     * Executed after validation
     *
     * @param array $data
     * @param ResetPasswordRequest $entity
     * @param Group $messages
     */
    public function afterValidation($data, ResetPasswordRequest $entity, $messages)
    {
        /** @var Validation\Message[] $validationMessages */
        $validationMessages = $this->_passwordValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);

        $validationMessages = $this->_emailValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);

        $validationMessages = $this->_codeValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);
    }
}