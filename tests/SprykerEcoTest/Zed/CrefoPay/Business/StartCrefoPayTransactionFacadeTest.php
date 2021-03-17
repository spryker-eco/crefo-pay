<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;
use SprykerEco\Zed\CrefoPay\Business\CrefoPayBusinessFactory;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

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
        // Arrange
        $mockConfig = $this->tester->mockEnvironmentConfig(
            CrefoPayConstants::USE_INDEPENDENT_ORDER_ID_FOR_TRANSACTION,
            true
        );
        $quoteTransfer = $this->tester->createQuoteTransfer();

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);
        $crefoPayTransactionTransfer = $quoteTransfer->getCrefoPayTransaction();

        //Assert
        $this->doTest($quoteTransfer);
        $this->assertStringContainsString(
            $quoteTransfer->getCustomer()->getCustomerReference(),
            $crefoPayTransactionTransfer->getCrefoPayOrderId()
        );
    }

    /**
     * @return void
     */
    public function testStartCrefoPayTransactionWithIndependentTransactionOrderId(): void
    {
        // Arrange
        $mockConfig = $this->tester->mockEnvironmentConfig(
            CrefoPayConstants::USE_INDEPENDENT_ORDER_ID_FOR_TRANSACTION,
            true
        );
        $quoteTransfer = $this->tester->createQuoteTransfer();

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);
        $crefoPayTransactionTransfer = $quoteTransfer->getCrefoPayTransaction();

        //Assert
        $this->doTest($quoteTransfer);
        $this->assertStringNotContainsString(
            $quoteTransfer->getCustomer()->getCustomerReference(),
            $crefoPayTransactionTransfer->getCrefoPayOrderId()
        );
        $this->assertEquals(
            30,
            strlen($crefoPayTransactionTransfer->getCrefoPayOrderId()),
            'OrderId has to consist of 30 characters.'
        );
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
