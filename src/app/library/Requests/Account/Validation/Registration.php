<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 9:40 AM
 */

namespace App\Library\Requests\Account\Validation;


use App\Library\Requests\Account\Registration as RegistrationRequest;
use App\Models\Players;
use App\Validation\Player\Password;
use Phalcon\Validation;
use Phalcon\Validation\Message\Group;
use Phalcon\Validation\Validator\Confirmation;

class Registration extends Validation
{
    /** @var Password */
    protected $_passwordValidation;

    public function initialize()
    {
        $this->_passwordValidation = new Password();

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
     * @param RegistrationRequest $entity
     * @param Group $messages
     */
    public function afterValidation($data, RegistrationRequest $entity, $messages)
    {
        /** @var Validation\Message[] $validationMessages */
        $validationMessages = $this->_passwordValidation->validate($data, $entity);
        $messages->appendMessages($validationMessages);

        $emailRegistered = Players::findFirst([
            'conditions' => 'Email = ?1',
            'bind' => [
                1 => $entity->Email
            ]
        ]);

        if($emailRegistered)
        {
            $messages->appendMessage(new Validation\Message('Email is already registered.', 'Email'));
        }
    }
}