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
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return void
     */
    public function executeCancelCommand(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): void {
        $this->getFactory()
            ->createCancelOmsCommand()
            ->execute($orderTransfer, $crefoPayToSalesOrderItemsCollection);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return void
     */
    public function executeCaptureCommand(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): void {
        $this->getFactory()
            ->createCaptureOmsCommand()
            ->execute($orderTransfer, $crefoPayToSalesOrderItemsCollection);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return void
     */
    public function executeRefundCommand(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): void {
        $this->getFactory()
            ->createRefundOmsCommand()
            ->execute($orderTransfer, $crefoPayToSalesOrderItemsCollection);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return void
     */
    public function executeFinishCommand(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): void {
        $this->getFactory()
            ->createFinishOmsCommand()
            ->execute($orderTransfer, $crefoPayToSalesOrderItemsCollection);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsReservedCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsReservedOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsAuthorizedCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsAuthorizedOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsWaitingForCaptureCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsWaitingForCaptureOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsCancellationPendingCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsCancellationPendingOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsCanceledCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsCanceledOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsExpiredCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsExpiredOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsCapturePendingCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsCapturePendingOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsCapturedCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsCapturedOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsFinishedCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsFinishedOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsDoneCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool
    {
        return $this->getFactory()
            ->createIsDoneOmsCondition()
            ->check($crefoPayToSalesOrderItemTransfer);
    }
}
