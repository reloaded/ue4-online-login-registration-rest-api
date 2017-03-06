<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 2/28/2017 4:15 PM
 */

namespace Reloaded\UnrealEngine4\Web\Controllers;

use Phalcon\Mvc\Controller;
use Zend\Mail\Transport\TransportInterface;

class ControllerBase extends Controller
{
    /** @var \JsonMapper */
    protected $_mapper;

    /** @var TransportInterface */
    protected $_mailTransport;

    /** @var \Phalcon\Config|mixed */
    protected $_appSettings;

    public function initialize()
    {
        $this->_mapper = $this->di->getShared('jsonMapper');
        $this->_mailTransport = $this->di->getShared('mailTransport');
        $this->_appSettings = $this->di->getShared('config')->application;
    }
}
