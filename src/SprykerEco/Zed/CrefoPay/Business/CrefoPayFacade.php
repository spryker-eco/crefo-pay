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
    public function executeCancelCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void
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
    public function executeCaptureCommand(OrderTransfer $orderTransfer, int $idSalesOrderItem): void
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
    public function executeRefundCommand(OrderTransfer $orderTransfer, int $idSalesOrderItem): void
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
    public function executeFinishCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void
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
    public function checkIsReservedCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsReservedOmsCondition()
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
    public function checkIsAuthorizedCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsAuthorizedOmsCondition()
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
    public function checkIsWaitingForCaptureCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsWaitingForCaptureOmsCondition()
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
    public function checkIsCancellationPendingCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCancellationPendingOmsCondition()
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
    public function checkIsCanceledCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCanceledOmsCondition()
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
    public function checkIsExpiredCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsExpiredOmsCondition()
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
    public function checkIsCapturePendingCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCapturePendingOmsCondition()
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
    public function checkIsCapturedCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCapturedOmsCondition()
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
    public function checkIsFinishedCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsFinishedOmsCondition()
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
    public function checkIsDoneCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsDoneOmsCondition()
            ->check($idSalesOrderItem);
    }
}
