<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayBusinessFactory getFactory()
 */
class CrefoPayFacade extends AbstractFacade implements CrefoPayFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function startCrefoPayTransaction(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createQuoteExpander()
            ->expand($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(
        PaymentMethodsTransfer $paymentMethodsTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentMethodsTransfer {
        return $this->getFactory()
            ->createPaymentMethodFilter()
            ->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    public function processNotification(CrefoPayNotificationTransfer $notificationTransfer): CrefoPayNotificationTransfer
    {
        return $this->getFactory()
            ->createCrefoPayNotificationProcessor()
            ->processNotification($notificationTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createOrderPaymentSaver()
            ->saveOrderPayment($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executePostSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        $this->getFactory()
            ->createCheckoutPostSaveHook()
            ->execute($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $salesOrderItemIds
     *
     * @return void
     */
    public function executeCancelOmsCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createCancelOmsCommand()
            ->execute($orderTransfer, $salesOrderItemIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function executeCaptureOmsCommand(OrderTransfer $orderTransfer, int $idSalesOrderItem): void
    {
        $this->getFactory()
            ->createCaptureOmsCommand()
            ->execute($orderTransfer, $idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idSalesOrderItem
     *
     * @return void
     */
    public function executeRefundOmsCommand(OrderTransfer $orderTransfer, int $idSalesOrderItem): void
    {
        $this->getFactory()
            ->createRefundOmsCommand()
            ->execute($orderTransfer, $idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int[] $salesOrderItemIds
     *
     * @return void
     */
    public function executeFinishOmsCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createFinishOmsCommand()
            ->execute($orderTransfer, $salesOrderItemIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsReserveCallSuccessfulOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsReserveCallSuccessfulOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsAcknowledgePendingReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsAcknowledgePendingReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsMerchantPendingReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsMerchantPendingReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCancelCallSuccessfulOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCancelCallSuccessfulOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCanceledReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCanceledReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsExpiredReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsExpiredReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCaptureCallSuccessfulOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCaptureCallSuccessfulOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsPaidReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsPaidReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsFinishCallSuccessfulOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsFinishCallSuccessfulOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsRefundCallSuccessfulOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsRefundCallSuccessfulOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsChargeBackReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsChargeBackReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsDoneReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsDoneReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }
}
