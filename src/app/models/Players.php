<?php

namespace Reloaded\UnrealEngine4\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Alnum;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\StringLength;
use Ramsey\Uuid\Uuid;

class Players extends AbstractPlayers
{
    public function beforeSave()
    {
        // Convert the GUID string into binary
        $this->Id = hex2bin(str_replace('-', '', $this->Id));
    }

    public function afterFetch()
    {
        // Convert the binary GUID to a GUID string
        $this->Id = Uuid::fromBytes($this->Id)->toString();
    }

    public function afterSave()
    {
        // Convert the binary GUID to a GUID string
        $this->Id = Uuid::fromBytes($this->Id)->toString();
    }

    public function validation()
    {
        $validation = new Validation();

        $validation->setFilters('InGameName', 'trim');

        $validation->add('InGameName', new StringLength([
            'max' => 25,
            'min' => 4
        ]));

        $validation->add('InGameName', new Alnum([
            'message' => 'In-Game Name must contain only alphanumeric characters.'
        ]));

        $validation->add('Email', new Email([
            'message' => 'Please enter a valid email.'
        ]));

        $validation->add('Email', new StringLength([
            'message' => 'We only accept emails up to 255 characters long.',
            'max' => 255,
            'min' => 6
        ]));

        $validation->setFilters(['FirstName', 'LastName'], 'trim');

        $validation->add(['FirstName', 'LastName'], new StringLength([
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

        $validation->add(['FirstName', 'LastName'], new Alpha([
            'message' => [
                'FirstName' => 'First name can only contain alphabetic characters.',
                'LastName' => 'Last name can only contain alphabetic characters.'
            ]
        ]));

        return $this->validate($validation);
    }
}
