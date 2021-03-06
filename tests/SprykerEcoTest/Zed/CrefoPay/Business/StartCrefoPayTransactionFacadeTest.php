<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group CrefoPay
 * @group Business
 */
class StartCrefoPayTransactionFacadeTest extends CrefoPayFacadeBaseTest
{
    /**
     * @return void
     */
    public function testStartCrefoPayTransaction(): void
    {
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);
        $this->doTest($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function doTest(QuoteTransfer $quoteTransfer): void
    {
        $crefoPayTransactionTransfer = $quoteTransfer->getCrefoPayTransaction();

        $this->assertTrue($crefoPayTransactionTransfer->getIsSuccess());
        $this->assertEquals(0, $crefoPayTransactionTransfer->getResultCode());
        $this->assertNotEmpty($crefoPayTransactionTransfer->getCrefoPayOrderId());
        $this->assertNotEmpty($crefoPayTransactionTransfer->getSalt());
        $this->assertGreaterThan(0, count($crefoPayTransactionTransfer->getAllowedPaymentMethods()));
    }
}
