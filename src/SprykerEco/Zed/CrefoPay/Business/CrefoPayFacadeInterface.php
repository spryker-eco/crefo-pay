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

interface CrefoPayFacadeInterface
{
    /**
     * Specification:
     *  - Starts transaction in CrefoPay system.
     *  - Expands QuoteTransfer with response from CreateTransaction API call.
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
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return void
     */
    public function executeCancelCommand(
        OrderTransfer $orderTransfer,
        CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
    ): void;

    /**
     * Specification:
     * - Makes capture request to CrefoPay API.
     * - Updates order items status.
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
    ): void;

    /**
     * Specification:
     * - Makes refund request to CrefoPay API.
     * - Updates order items status.
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
    ): void;

    /**
     * Specification:
     * - Makes finish request to CrefoPay API.
     * - Updates order items status.
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
    ): void;

    /**
     * Specification:
     * - Checks if reserve api call was successfully performed for given order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsReservedCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;

    /**
     * Specification:
     * - Checks if ACKNOWLEDGEPENDING notification was received for given order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsAuthorizedCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;

    /**
     * Specification:
     * - Checks if MERCHANTPENDING notification was received for given order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsWaitingForCaptureCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;

    /**
     * Specification:
     * - Checks if cancel api call was successfully performed for given order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsCancellationPendingCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;

    /**
     * Specification:
     * - Checks if CANCELLED notification was received for given order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsCanceledCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;

    /**
     * Specification:
     * - Checks if EXPIRED notification was received for given order item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer
     *
     * @return bool
     */
    public function checkIsExpiredCondition(CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer): bool;
}
