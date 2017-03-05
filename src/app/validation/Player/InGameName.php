<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/4/2017 4:04 PM
 */

namespace App\Validation\Player;


use Phalcon\Validation;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\StringLength;

/**
 * Validates an In-Game name that is associated to a Player model.
 *
 * @package App\Validators\Player
 */
class InGameName extends Validation
{
    public function initialize()
    {
        $this->setFilters('InGameName', 'trim');

        $this->add('InGameName', new StringLength([
            'max' => 25,
            'min' => 4,
            'message' => 'In-Game name must be between 4 and 25 characters.'
        ]));

        $this->add('InGameName', new Alnum([
            'message' => 'In-Game Name must contain only alphanumeric characters.'
        ]));
    }
}