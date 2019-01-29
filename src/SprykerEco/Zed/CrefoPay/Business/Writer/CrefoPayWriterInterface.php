<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Writer;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

interface CrefoPayWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function createPaymentEntities(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void;

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null $paymentCrefoPayTransfer
     *
     * @param int|null $crefoPayApiLogId
     *
     * @return void
     */
    public function updatePaymentEntities(
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection,
        ?PaymentCrefoPayTransfer $paymentCrefoPayTransfer = null,
        ?int $crefoPayApiLogId = null
    ): void;

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return void
     */
    public function createNotificationEntities(
        CrefoPayNotificationTransfer $notificationTransfer,
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): void;
}
