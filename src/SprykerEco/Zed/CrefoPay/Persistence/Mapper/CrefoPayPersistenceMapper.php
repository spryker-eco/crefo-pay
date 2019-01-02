<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence\Mapper;

use Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayApiLogEntityTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer;
use Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer;

class CrefoPayPersistenceMapper implements CrefoPayPersistenceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer
     */
    public function mapPaymentCrefoPayTransferToEntityTransfer(
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer
    ): SpyPaymentCrefoPayEntityTransfer {
        $paymentCrefoPayEntityTransfer = (new SpyPaymentCrefoPayEntityTransfer())
            ->fromArray($paymentCrefoPayTransfer->modifiedToArray(), true);

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
        $paymentCrefoPayTransfer->fromArray($paymentCrefoPayEntityTransfer->toArray(), true);

        return $paymentCrefoPayTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer
     */
    public function mapPaymentCrefoPayOrderItemTransferToEntityTransfer(
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
    ): SpyPaymentCrefoPayOrderItemEntityTransfer {
        $paymentCrefoPayOrderItemEntityTransfer = (new SpyPaymentCrefoPayOrderItemEntityTransfer())
            ->fromArray($paymentCrefoPayOrderItemTransfer->modifiedToArray(), true);

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
     * @param \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer $paymentCrefoPayApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayApiLogEntityTransfer
     */
    public function mapPaymentCrefoPayApiLogTransferToEntityTransfer(
        PaymentCrefoPayApiLogTransfer $paymentCrefoPayApiLogTransfer
    ): SpyPaymentCrefoPayApiLogEntityTransfer {
        $paymentCrefoPayApiLogEntityTransfer = (new SpyPaymentCrefoPayApiLogEntityTransfer())
            ->fromArray($paymentCrefoPayApiLogTransfer->modifiedToArray(), true);

        return $paymentCrefoPayApiLogEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyPaymentCrefoPayApiLogEntityTransfer $paymentCrefoPayApiLogEntityTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer $paymentCrefoPayApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer
     */
    public function mapEntityTransferToPaymentCrefoPayApiLogTransfer(
        SpyPaymentCrefoPayApiLogEntityTransfer $paymentCrefoPayApiLogEntityTransfer,
        PaymentCrefoPayApiLogTransfer $paymentCrefoPayApiLogTransfer
    ): PaymentCrefoPayApiLogTransfer {
        $paymentCrefoPayApiLogTransfer->fromArray(
            $paymentCrefoPayApiLogEntityTransfer->toArray(),
            true
        );

        return $paymentCrefoPayApiLogTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer
     */
    public function mapPaymentCrefoPayNotificationTransferToEntityTransfer(
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
    ): SpyPaymentCrefoPayNotificationEntityTransfer {
        $paymentCrefoPayNotificationEntityTransfer = (new SpyPaymentCrefoPayNotificationEntityTransfer())
            ->fromArray($paymentCrefoPayNotificationTransfer->modifiedToArray(), true);

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
