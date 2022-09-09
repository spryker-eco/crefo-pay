<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\ItemTransfer;
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
     * @uses \SprykerEco\Service\CrefoPay\CrefoPayConfig::CREFO_PAY_ORDER_ID_LENGTH
     *
     * @var int
     */
    protected const CREFO_PAY_ORDER_ID_LENGTH = 30;

    /**
     * @uses \SprykerEco\Service\CrefoPay\CrefoPayConfig::CREFO_PAY_USER_ID_MAX_LENGTH
     *
     * @var int
     */
    protected const CREFO_PAY_USER_ID_MAX_LENGTH = 50;

    /**
     * @uses \SprykerEco\Service\CrefoPay\CrefoPayConfig::CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH
     *
     * @var int
     */
    protected const CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH = 20;

    /**
     * @return void
     */
    public function testStartCrefoPayTransaction(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer();

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);
        $crefoPayTransactionTransfer = $quoteTransfer->getCrefoPayTransaction();

        // Assert
        $this->doTest($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testStartCrefoPayTransactionShouldReturnSuccessWhenCustomerReferenceLengthHigherThanOrderIdMaxLength(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer(
            $this->tester->generateRandomString(static::CREFO_PAY_ORDER_ID_LENGTH + 1),
        );

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);

        // Assert
        $this->doTest($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testStartCrefoPayTransactionShouldReturnSuccessWhenCustomerReferenceLengthHigherThanUserIdMaxLength(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer(
            $this->tester->generateRandomString(static::CREFO_PAY_USER_ID_MAX_LENGTH + 1),
        );

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);

        // Assert
        $this->doTest($quoteTransfer);
    }

    /**
     * @return void
     */
    public function testStartCrefoPayTransactionShouldReturnSuccessWhenSkuLengthHigherThanBasketItemIdMaxLength(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->createQuoteTransfer();
        $quoteTransfer->addItem($this->tester->createItemTransfer([
            ItemTransfer::SKU => $this->tester->generateRandomString(static::CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH + 1),
        ]));

        // Act
        $quoteTransfer = $this->facade->startCrefoPayTransaction($quoteTransfer);

        // Assert
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
        $this->assertSame(
            30,
            strlen($crefoPayTransactionTransfer->getCrefoPayOrderId()),
            'OrderId has to consist of 30 characters.',
        );
    }
}
