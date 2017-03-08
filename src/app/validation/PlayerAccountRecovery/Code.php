<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/7/2017 11:28 PM
 */

namespace App\Validation\PlayerAccountRecovery;


use Phalcon\Validation;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\StringLength;

/**
 * Validates a random unique recovery code associated to a PlayerAccountRecovery model.
 *
 * @package App\Validation\PlayerAccountRecovery
 */
class Code extends Validation
{
    public function initialize()
    {
        $this->add(
            'Code',
            new StringLength([
                'min' => 10,
                'max' => 10,
                'messageMinimum' => 'Code must be 10 characters.',
                'messageMaximum' => 'Code must be 10 characters.'
            ])
        );

        $this->add(
            'Code',
            new Alnum([
                'message' => 'Code must have only alphanumeric characters.'
            ])
        );
    }
}