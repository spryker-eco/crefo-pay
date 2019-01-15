<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer;
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
     * @param \Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
     *
     * @return void
     */
    public function executeCancelCommand(
        OrderTransfer $orderTransfer,
        CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
    ): void;

    /**
     * Specification:
     * - Makes capture request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
     *
     * @return void
     */
    public function executeCaptureCommand(
        OrderTransfer $orderTransfer,
        CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
    ): void;

    /**
     * Specification:
     * - Makes refund request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
     *
     * @return void
     */
    public function executeRefundCommand(
        OrderTransfer $orderTransfer,
        CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
    ): void;

    /**
     * Specification:
     * - Makes finish request to CrefoPay API.
     * - Updates order items status.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
     *
     * @return void
     */
    public function executeFinishCommand(
        OrderTransfer $orderTransfer,
        CrefoPayOrderItemsDataTransfer $orderItemsDataTransfer
    ): void;
}
