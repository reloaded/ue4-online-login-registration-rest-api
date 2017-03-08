<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 12:48 AM
 */

namespace App\Library\Requests\Account;


use App\Library\Requests\AbstractRequest;

class RecoverPassword extends AbstractRequest
{
    /** @var string */
    public $Email;
}