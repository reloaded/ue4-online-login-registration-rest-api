<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 9:40 AM
 */

namespace App\Library\Requests;


use Phalcon\Validation;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;

class RegistrationRequestValidator extends Validation
{
    public function initialize()
    {
        // 1 uppercase letter
        $this->add(
            'Password',
            new Regex([
                'pattern' => '/(.*)[A-Z](.*)/',
                'message' => 'Password must have at least 1 uppercase letter.'
            ])
        );

        // 2 lowercase letters
        $this->add(
            'Password',
            new Regex([
                'pattern' => '/(.*)[a-z]{2,}(.*)/',
                'message' => 'Password must have at least 2 lowercase letters.'
            ])
        );

        // 1 digit
        $this->add(
            'Password',
            new Regex([
                'pattern' => '/(.*)[0-9](.*)/',
                'message' => 'Password must have at least 1 digit.'
            ])
        );

        // 2 special characters
        $this->add(
            'Password',
            new Regex([
                'pattern' => '/(.*)[!@#$%^&*()\-_=+{};:,<.>]{2,}(.*)/',
                'message' => 'Password must have at least 2 special characters.'
            ])
        );

        $this->add(
            'Password',
            new StringLength([
                'min' => 6,
                'minimumMessage' => 'Password must be at least 6 characters.'
            ])
        );

        $this->add(
            'Password',
            new Confirmation([
                'with' => 'PasswordConfirm',
                'message' => 'Passwords do not match.'
            ])
        );
    }
}