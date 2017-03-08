<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/6/2017 11:03 PM
 */

namespace App\Library\Requests\Account;


use App\Library\Requests\AbstractRequest;

class Activate extends AbstractRequest
{
    /** @var string */
    public $Email;

    /** @var string */
    public $Code;

    /** @var string */
    public $Password;
}