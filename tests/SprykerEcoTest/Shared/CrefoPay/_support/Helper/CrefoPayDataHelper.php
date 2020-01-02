<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Shared\CrefoPay\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CrefoPayDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function haveCrefoPayEntities(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getCrefopayFacade()
            ->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface
     */
    private function getCrefopayFacade(): CrefoPayFacadeInterface
    {
        return $this->getLocator()->crefoPay()->facade();
    }
}
