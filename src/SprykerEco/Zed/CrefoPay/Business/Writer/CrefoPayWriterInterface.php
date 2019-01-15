<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Writer;

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
    public function savePaymentEntities(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void;

    /**
     * @param string $status
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollectionTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null $paymentCrefoPayTransfer
     *
     * @return void
     */
    public function updatePaymentEntities(
        string $status,
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollectionTransfer,
        ?PaymentCrefoPayTransfer $paymentCrefoPayTransfer = null
    ): void;
}
