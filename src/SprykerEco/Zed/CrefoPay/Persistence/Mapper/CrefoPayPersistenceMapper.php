<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence\Mapper;

use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer;

class CrefoPayPersistenceMapper implements CrefoPayPersistenceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer $paymentCrefoPayEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer
     */
    public function mapPaymentCrefoPayTransferToEntityTransfer(
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer,
        SpyPaymentCrefoPayEntityTransfer $paymentCrefoPayEntityTransfer
    ): SpyPaymentCrefoPayEntityTransfer {
        $paymentCrefoPayEntityTransfer->fromArray(
            $paymentCrefoPayTransfer->modifiedToArray(),
            true
        );

        return $paymentCrefoPayEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer $paymentCrefoPayEntityTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function mapEntityTransferToPaymentCrefoPayTransfer(
        SpyPaymentCrefoPayEntityTransfer $paymentCrefoPayEntityTransfer,
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer
    ): PaymentCrefoPayTransfer {
        $paymentCrefoPayTransfer->fromArray(
            $paymentCrefoPayEntityTransfer->toArray(),
            true
        );

        return $paymentCrefoPayTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer $paymentCrefoPayOrderItemEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer
     */
    public function mapPaymentCrefoPayOrderItemTransferToEntityTransfer(
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer,
        SpyPaymentCrefoPayOrderItemEntityTransfer $paymentCrefoPayOrderItemEntityTransfer
    ): SpyPaymentCrefoPayOrderItemEntityTransfer {
        $paymentCrefoPayOrderItemEntityTransfer->fromArray(
            $paymentCrefoPayOrderItemTransfer->modifiedToArray(),
            true
        );

        return $paymentCrefoPayOrderItemEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer $paymentCrefoPayOrderItemEntityTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    public function mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
        SpyPaymentCrefoPayOrderItemEntityTransfer $paymentCrefoPayOrderItemEntityTransfer,
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
    ): PaymentCrefoPayOrderItemTransfer {
        $paymentCrefoPayOrderItemTransfer->fromArray(
            $paymentCrefoPayOrderItemEntityTransfer->toArray(),
            true
        );

        return $paymentCrefoPayOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer $paymentCrefoPayNotificationEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer
     */
    public function mapPaymentCrefoPayNotificationTransferToEntityTransfer(
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer,
        SpyPaymentCrefoPayNotificationEntityTransfer $paymentCrefoPayNotificationEntityTransfer
    ): SpyPaymentCrefoPayNotificationEntityTransfer {
        $paymentCrefoPayNotificationEntityTransfer->fromArray(
            $paymentCrefoPayNotificationTransfer->modifiedToArray(),
            true
        );

        return $paymentCrefoPayNotificationEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer $paymentCrefoPayNotificationEntityTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer
     */
    public function mapEntityTransferToPaymentCrefoPayNotificationTransfer(
        SpyPaymentCrefoPayNotificationEntityTransfer $paymentCrefoPayNotificationEntityTransfer,
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
    ): PaymentCrefoPayNotificationTransfer {
        $paymentCrefoPayNotificationTransfer->fromArray(
            $paymentCrefoPayNotificationEntityTransfer->toArray(),
            true
        );

        return $paymentCrefoPayNotificationTransfer;
    }
}
