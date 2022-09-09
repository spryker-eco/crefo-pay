<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEcoTest\Service\CrefoPay;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @group Functional
 * @group SprykerEco
 * @group Service
 * @group CrefoPay
 * @group CrefoPayServiceTest
 * Add your own group annotations below this line
 */
class CrefoPayServiceTest extends Unit
{
    /**
     * @var \SprykerEcoTest\Service\CrefoPay\CrefoPayServiceTester
     */
    protected CrefoPayServiceTester $tester;

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
     * @uses \SprykerEco\Service\CrefoPay\Generator\CrefoPayUserIdGenerator::USER_ID_B2C_SUFFIX
     *
     * @var string
     */
    protected const USER_ID_B2C_SUFFIX = '-B2C';

    /**
     * @uses \SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGenerator::RANDOM_STRING_MIN_LENGTH
     *
     * @var int
     */
    protected const RANDOM_STRING_MIN_LENGTH = 3;

    /**
     * @return void
     */
    public function testGenerateCrefoPayOrderIdShouldReturnValidOrderIdWhenCustomerReferenceLengthHigherThenMaxLength(): void
    {
        // Arrange
        $customerReference = $this->tester->generateRandomString(static::CREFO_PAY_ORDER_ID_LENGTH + 1);
        $quoteTransfer = (new QuoteTransfer())->setCustomerReference($customerReference);
        $expectedOrderIdPrefix = substr($customerReference, 0, static::RANDOM_STRING_MIN_LENGTH + 2);

        // Act
        $orderId = $this->tester->getService()->generateCrefoPayOrderId($quoteTransfer);

        // Assert
        $this->assertSame(static::CREFO_PAY_ORDER_ID_LENGTH, strlen($orderId));
        $this->assertNotFalse(strpos($orderId, $expectedOrderIdPrefix));
    }

    /**
     * @return void
     */
    public function testGenerateCrefoPayBasketItemIdShouldReturnValidBasketItemIdWhenSkuLengthHigherThenMaxLength(): void
    {
        // Arrange
        $sku = $this->tester->generateRandomString(static::CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH + 1);
        $itemTransfer = (new ItemTransfer())->setSku($sku);
        $expectedBasketItemIdPrefix = substr($sku, 0, static::RANDOM_STRING_MIN_LENGTH + 2);

        // Act
        $basketItemId = $this->tester->getService()->generateCrefoPayBasketItemId($itemTransfer);

        // Assert
        $this->assertSame(static::CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH, strlen($basketItemId));
        $this->assertNotFalse(strpos($basketItemId, $expectedBasketItemIdPrefix));
    }

    /**
     * @return void
     */
    public function testGenerateCrefoPayBasketItemIdShouldReturnSkuWhenSkuHasValidLength(): void
    {
        // Arrange
        $sku = $this->tester->generateRandomString(static::CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH);
        $itemTransfer = (new ItemTransfer())->setSku($sku);

        // Act
        $basketItemId = $this->tester->getService()->generateCrefoPayBasketItemId($itemTransfer);

        // Assert
        $this->assertSame($sku, $basketItemId);
    }

    /**
     * @return void
     */
    public function testGenerateCrefoPayUserIdShouldReturnValidUserIdWhenCustomerReferenceLengthHigherThenMaxLength(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer((new CustomerTransfer()))
            ->setCustomerReference(
                $this->tester->generateRandomString(static::CREFO_PAY_USER_ID_MAX_LENGTH + 1),
            );

        // Act
        $userId = $this->tester->getService()->generateCrefoPayUserId($quoteTransfer);

        // Assert
        $this->assertSame(static::CREFO_PAY_USER_ID_MAX_LENGTH, strlen($userId));
    }

    /**
     * @return void
     */
    public function testGenerateCrefoPayUserIdShouldContainCustomerReferenceWhenNotGuestCustomer(): void
    {
        // Arrange
        $customerReference = $this->tester->generateRandomString(static::CREFO_PAY_USER_ID_MAX_LENGTH - 4);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer((new CustomerTransfer()))
            ->setCustomerReference($customerReference);

        $expectedUserId = sprintf('%s%s', $customerReference, static::USER_ID_B2C_SUFFIX);

        // Act
        $userId = $this->tester->getService()->generateCrefoPayUserId($quoteTransfer);

        // Assert
        $this->assertSame($expectedUserId, $userId);
    }

    /**
     * @return void
     */
    public function testGenerateCrefoPayUserIdShouldNotContainCustomerReferenceWhenGuestCustomer(): void
    {
        // Arrange
        $customerReference = $this->tester->generateRandomString(static::CREFO_PAY_USER_ID_MAX_LENGTH);
        $customerTransfer = (new CustomerTransfer())->setIsGuest(true);
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($customerReference);

        // Act
        $userId = $this->tester->getService()->generateCrefoPayUserId($quoteTransfer);

        // Assert
        $this->assertFalse(strpos($userId, $customerReference));
        $this->assertNotFalse(strpos($userId, 'GUEST-USER-'));
    }
}
