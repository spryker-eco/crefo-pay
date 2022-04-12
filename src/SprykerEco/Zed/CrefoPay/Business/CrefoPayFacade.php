<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayBusinessFactory getFactory()
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayRepositoryInterface getRepository()
 */
class CrefoPayFacade extends AbstractFacade implements CrefoPayFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function executeCaptureOmsCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void
    {
        $this->getFactory()
            ->createCaptureOmsCommand()
            ->execute($orderTransfer, $salesOrderItemIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function executeRefundOmsCommand(
        RefundTransfer $refundTransfer,
        OrderTransfer $orderTransfer,
        array $salesOrderItemIds
    ): void {
        $this->getFactory()
            ->createRefundOmsCommand()
            ->execute($refundTransfer, $orderTransfer, $salesOrderItemIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCiaPendingReceivedOmsCondition(int $idSalesOrderItem): bool
    {
        return $this->getFactory()
            ->createIsCiaPendingReceivedOmsCondition()
            ->check($idSalesOrderItem);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
