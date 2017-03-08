<?php
/**
 * @author Jason Harris <1337reloaded@gmail.com>
 * @date 3/8/2017 1:08 AM
 */

namespace App\Library\EmailMessages\AccountRecovery;

use App\Library\EmailMessages\AbstractMailTemplate;
use App\Models\PlayerAccountRecovery;
use App\Models\Players;


/**
 * Renders a message providing the player details on how to reset their account password.
 *
 * @package App\Library\EmailMessages\AccountRecovery
 */
class RecoverPasswordTemplate extends AbstractMailTemplate
{
    /** @var Players */
    protected $_player;

    /** @var PlayerAccountRecovery */
    protected $_activationInfo;

    #region Constructors

    public function __construct(Players $player, PlayerAccountRecovery $activationInfo)
    {
        parent::__construct();

        $this->_player = $player;
        $this->_activationInfo = $activationInfo;
    }

    #endregion

    /**
     * @inheritDoc
     */
    public function renderHtml(): string
    {
        $this->_view->setViewsDir(__DIR__ . DIRECTORY_SEPARATOR);

        $this->_view
            ->setVar('ResetCode', $this->_activationInfo->getCode())
            ->setVar('CodeExpiration', $this->_activationInfo->getExpiration())
            ->setVar('InGameName', $this->_player->getInGameName())
            ->setVar('GeneratedOn', $this->_activationInfo->getGeneratedOn());

        return $this->_view->render('RecoverPassword.html.phtml');
    }


}