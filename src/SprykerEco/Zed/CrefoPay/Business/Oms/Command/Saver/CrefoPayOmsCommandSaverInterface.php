<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer;

interface CrefoPayOmsCommandSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer
     *
     * @return void
     */
    public function savePaymentEntities(SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer): void;
}
