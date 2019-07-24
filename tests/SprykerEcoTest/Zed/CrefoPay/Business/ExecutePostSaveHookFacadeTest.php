<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group CrefoPay
 * @group Business
 */
class ExecutePostSaveHookFacadeTest extends CrefoPayFacadeBaseTest
{
    /**
     * @return void
     */
    public function testExecutePostSaveHook(): void
    {
        $this->markTestSkipped('Will be implemented soon...');

        $quoteTransfer = $this->tester->createQuoteTransfer();
        $checkoutResponseTransfer = $this->tester->createCheckoutResponseTransfer();
        $this->facade->executePostSaveHook($quoteTransfer, $checkoutResponseTransfer);
        $this->doTest();
    }

    /**
     * @return void
     */
    public function doTest(): void
    {
    }
}
