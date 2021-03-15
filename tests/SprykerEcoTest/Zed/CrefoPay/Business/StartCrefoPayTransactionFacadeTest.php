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
     * @dataProvider startCrefoPayTransaction
     *
     * @return void
     */
    public function testStartCrefoPayTransaction(bool $useIndependentOrderId): void
    {
        // Arrange
        $mockConfig = $this->tester->mockEnvironmentConfig(
            CrefoPayConstants::USE_INDEPENDENT_ORDER_ID_FOR_TRANSACTION,
            $useIndependentOrderId
        );
        $quoteTransfer = $this->tester->createQuoteTransfer();

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);

        //Assert
        $this->doTest($quoteTransfer, $useIndependentOrderId);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param bool $useIndependentOrderId
     *
     * @return void
     */
    public function doTest(QuoteTransfer $quoteTransfer, bool $useIndependentOrderId): void
    {
        $crefoPayTransactionTransfer = $quoteTransfer->getCrefoPayTransaction();

        $this->assertTrue($crefoPayTransactionTransfer->getIsSuccess());
        $this->assertEquals(0, $crefoPayTransactionTransfer->getResultCode());
        $this->assertNotEmpty($crefoPayTransactionTransfer->getCrefoPayOrderId());
        $this->assertNotEmpty($crefoPayTransactionTransfer->getSalt());
        $this->assertGreaterThan(0, count($crefoPayTransactionTransfer->getAllowedPaymentMethods()));

        if ($useIndependentOrderId) {
            $this->assertStringNotContainsString(
                $quoteTransfer->getCustomer()->getCustomerReference(),
                $crefoPayTransactionTransfer->getCrefoPayOrderId()
            );
            $this->assertEquals(
                30,
                strlen($crefoPayTransactionTransfer->getCrefoPayOrderId()),
                'OrderId has to consist of 30 characters.'
            );
        } else {
            $this->assertStringContainsString(
                $quoteTransfer->getCustomer()->getCustomerReference(),
                $crefoPayTransactionTransfer->getCrefoPayOrderId()
            );
        }
    }

    /**
     * @return bool[][]
     */
    public function startCrefoPayTransaction(): array
    {
        return [
            [false],
            [true],
        ];
    }
}
