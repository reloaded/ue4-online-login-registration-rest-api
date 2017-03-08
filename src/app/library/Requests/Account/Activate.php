<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/6/2017 11:03 PM
 */

namespace App\Library\Requests\Account;


class Activate extends BaseRequest
{
    /** @var string */
    public $Email;

    /** @var string */
    public $Code;

    /** @var string */
    public $Password;
}