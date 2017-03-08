<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace App\Library\Requests\Account;


use App\Library\Requests\BaseRequest;

class Registration extends BaseRequest
{
    /** @var string */
    public $Email;

    /** @var string */
    public $Password;

    /** @var string */
    public $PasswordConfirm;

    /** @var string */
    public $InGameName;

    /** @var string */
    public $FirstName;

    /** @var string */
    public $LastName;
}