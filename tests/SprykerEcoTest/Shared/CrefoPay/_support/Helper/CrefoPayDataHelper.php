<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerEcoTest\Shared\CrefoPay\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
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
    public function haveCrefoPayEntities(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer)
    {
        $this->getCrefopayFacade()
            ->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface
     */
    private function getCrefopayFacade()
    {
        return $this->getLocator()->crefoPay()->facade();
    }
}
