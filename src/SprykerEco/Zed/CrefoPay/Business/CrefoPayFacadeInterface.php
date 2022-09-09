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

interface CrefoPayFacadeInterface
{
    /**
     * Specification:
     * - Requires `QuoteTransfer.customer` transfer property to be set.
     * - Starts transaction in CrefoPay system.
     * - Expands QuoteTransfer with response from CreateTransaction API call.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function startCrefoPayTransaction(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     *  - Filters available payment methods by response from CreateTransaction API call.
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
    ): PaymentMethodsTransfer;

    /**
     * Specification:
     *  - Saves notification.
     *  - Updates order items status depends on notification transaction/order status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    public function processNotification(CrefoPayNotificationTransfer $notificationTransfer): CrefoPayNotificationTransfer;

    /**
     * Specification:
     * - Creates payment entities and saves them to DB.
     * - Saves order payment method data according to quote and checkout response transfer data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void;

    /**
     * Specification:
     * - Makes reserve request to CrefoPay API.
     * - Updates payment entities and saves them to DB.
     * - Updates order items with necessary OMS statuses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function executePostSaveHook(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void;

    /**
     * Specification:
     * - Makes cancel request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function executeCancelOmsCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void;

    /**
     * Specification:
     * - Makes capture request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function executeCaptureOmsCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void;

    /**
     * Specification:
     * - Makes refund request to CrefoPay API.
     * - Updates order items status.
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
    ): void;

    /**
     * Specification:
     * - Makes finish request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $salesOrderItemIds
     *
     * @return void
     */
    public function executeFinishOmsCommand(OrderTransfer $orderTransfer, array $salesOrderItemIds): void;

    /**
     * Specification:
     * - Checks if reserve api call was successfully performed for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsReserveCallSuccessfulOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if ACKNOWLEDGEPENDING notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsAcknowledgePendingReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if MERCHANTPENDING notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsMerchantPendingReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if CIAPENDING notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCiaPendingReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if cancel api call was successfully performed for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCancelCallSuccessfulOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if CANCELLED notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCanceledReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if EXPIRED notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsExpiredReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if capture API call was successfully performed for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsCaptureCallSuccessfulOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if PAID notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsPaidReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if finish API call was successfully performed for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsFinishCallSuccessfulOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if refund API call was successfully performed for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsRefundCallSuccessfulOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if CHARGEBACK notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsChargeBackReceivedOmsCondition(int $idSalesOrderItem): bool;

    /**
     * Specification:
     * - Checks if DONE notification was received for given order item.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function checkIsDoneReceivedOmsCondition(int $idSalesOrderItem): bool;
}
