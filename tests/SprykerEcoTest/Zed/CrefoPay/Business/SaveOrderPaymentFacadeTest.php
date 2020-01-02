<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

/**
 * @group Functional
 * @group SprykerEco
 * @group Zed
 * @group CrefoPay
 * @group Business
 */
class SaveOrderPaymentFacadeTest extends CrefoPayFacadeBaseTest
{
    /**
     * @return void
     */
    public function testSaveOrderPayment(): void
    {
        $this->markTestSkipped('Will be implemented soon...');

        $quoteTransfer = $this->tester->createQuoteTransfer();
        $saveOrderTransfer = $this->tester->createSaveOrderTransfer();
        $this->facade->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
        $this->doTest();
    }

    /**
     * @return void
     */
    public function doTest(): void
    {
    }
}
