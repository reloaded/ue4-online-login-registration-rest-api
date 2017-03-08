<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 12:48 AM
 */

namespace App\Library\Requests\Account;


use App\Library\Requests\BaseRequest;

class RecoverPassword extends BaseRequest
{
    /** @var string */
    public $Email;
}