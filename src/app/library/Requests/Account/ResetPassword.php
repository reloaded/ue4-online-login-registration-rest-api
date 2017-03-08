<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 1:33 AM
 */

namespace App\Library\Requests\Account;


use App\Library\Requests\AbstractRequest;

class ResetPassword extends AbstractRequest
{
    /** @var string */
    public $Email;

    /** @var string */
    public $Code;

    /** @var string */
    public $Password;

    /** @var string */
    public $PasswordConfirm;
}