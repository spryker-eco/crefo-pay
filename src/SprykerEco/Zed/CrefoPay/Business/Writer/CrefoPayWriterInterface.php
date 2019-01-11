<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Writer;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer;
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
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[] $paymentCrefoPayOrderItemTransfers
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer|null $paymentCrefoPayTransfer
     *
     * @return void
     */
    public function updatePaymentEntities(
        string $status,
        array $paymentCrefoPayOrderItemTransfers,
        ?PaymentCrefoPayTransfer $paymentCrefoPayTransfer = null
    ): void;

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $request
     * @param \Generated\Shared\Transfer\CrefoPayApiResponseTransfer $response
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer
     */
    public function saveApiLog(
        string $type,
        CrefoPayApiRequestTransfer $request,
        CrefoPayApiResponseTransfer $response
    ): PaymentCrefoPayApiLogTransfer;
}
