<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 7:17 PM
 */

namespace App\Validation\Player;


use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as PhalconEmailValidator;
use Phalcon\Validation\Validator\StringLength;

/**
 * Validates an email that is associated to a Player model.
 *
 * @package App\Validators\Player
 */
class Email extends Validation
{
    public function initialize()
    {
        $this->add('Email', new PhalconEmailValidator([
            'message' => 'Please enter a valid email.'
        ]));

        $this->add('Email', new StringLength([
            'message' => 'We only accept emails up to 255 characters long.',
            'max' => 255,
            'min' => 6
        ]));
    }
}