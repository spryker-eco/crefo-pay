<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication\Controller;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function startCrefoPayTransactionAction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->startCrefoPayTransaction($quoteTransfer);
    }

    public function processNotificationAction(CrefoPayNotificationTransfer $notificationTransfer): CrefoPayNotificationTransfer
    {
        return $this->getFacade()->processNotificationAction($notificationTransfer);
    }
}
