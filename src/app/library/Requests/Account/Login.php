<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/3/2017 7:14 PM
 */

namespace App\Library\Requests\Account;


use App\Library\Requests\AbstractRequest;

class Login extends AbstractRequest
{
    /** @var string */
    public $Email;

    /** @var string */
    public $Password;
}