<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/4/2017 4:17 PM
 */

namespace App\Validation\Player;


use Phalcon\Validation;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\StringLength;

/**
 * Validates a player's personal first and last name.
 *
 * @package App\Validators\Player
 */
class PersonalName extends Validation
{
    public function initialize()
    {
        $this->setFilters(['FirstName', 'LastName'], 'trim');

        $this->add(['FirstName', 'LastName'], new StringLength([
            'max' => 50,
            'min' => 2,
            'messageMaximum' => [
                'FirstName' => 'First name can only be up to 50 characters long.',
                'LastName' => 'Last name can only be up to 50 characters long.'
            ],
            'messageMinimum' => [
                'FirstName' => 'First name must be at least 2 characters.',
                'LastName' => 'Last name must be at least 2 characters.'
            ]
        ]));

        $this->add(['FirstName', 'LastName'], new Alpha([
            'message' => [
                'FirstName' => 'First name can only contain alphabetic characters.',
                'LastName' => 'Last name can only contain alphabetic characters.'
            ]
        ]));
    }
}