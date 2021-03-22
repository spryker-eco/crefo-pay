<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command;

use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;

class CaptureOmsCommand extends CrefoPayOmsCommandByOrder implements CrefoPayOmsCommandInterface
{
    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function performApiCall(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        if (
            $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCapturedAmount() === 0
            && $crefoPayOmsCommandTransfer->getExpensesRequest() !== null
        ) {
            $expensesResponseTransfer = $this->omsCommandClient
                ->performApiCall($crefoPayOmsCommandTransfer->getExpensesRequest());

            $crefoPayOmsCommandTransfer->setExpensesResponse($expensesResponseTransfer);
        }

        return parent::performApiCall($crefoPayOmsCommandTransfer);
    }
}
