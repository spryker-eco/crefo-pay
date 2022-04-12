<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

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
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $paymentMethodsTransfer = $this->tester->createPaymentMethodsTransfer();
        $filteredPaymentMethodsTransfer = $this->facade->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
        $this->doTest($quoteTransfer, $filteredPaymentMethodsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $filteredPaymentMethodsTransfer
     *
     * @return void
     */
    public function doTest(QuoteTransfer $quoteTransfer, PaymentMethodsTransfer $filteredPaymentMethodsTransfer): void
    {
        $this->assertCount(
            count($quoteTransfer->getCrefoPayTransaction()->getAllowedPaymentMethods()),
            $filteredPaymentMethodsTransfer->getMethods(),
        );
    }
}
