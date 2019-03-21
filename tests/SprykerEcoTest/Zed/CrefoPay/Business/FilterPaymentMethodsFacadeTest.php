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
class FilterPaymentMethodsFacadeTest extends CrefoPayFacadeBaseTest
{
    /**
     * @return void
     */
    public function testFilterPaymentMethods(): void
    {
        $this->markTestSkipped('Will be implemented soon...');

        $quoteTransfer = $this->tester->createQuoteTransfer();
        $paymentMethodsTransfer = $this->tester->createPaymentMethodsTransfer();
        $paymentMethodsTransfer = $this->facade->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
        $this->doTest($paymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return void
     */
    public function doTest(PaymentMethodsTransfer $paymentMethodsTransfer): void
    {
    }
}
