<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/4/2017 5:04 PM
 */

namespace App\Validation\Player;


use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\StringLength;

class Password extends Validation
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
    }
}