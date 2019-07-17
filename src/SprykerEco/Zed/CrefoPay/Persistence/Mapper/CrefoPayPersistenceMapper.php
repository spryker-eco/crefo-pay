<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPay;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotification;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLog;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotification;
use Propel\Runtime\Collection\ObjectCollection;

class CrefoPayPersistenceMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem[] $paymentCrefoPayOrderItemEntities
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $crefoPayOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    public function mapOrderItemEntitiesToOrderItemCollection(
        ObjectCollection $paymentCrefoPayOrderItemEntities,
        PaymentCrefoPayOrderItemCollectionTransfer $crefoPayOrderItemCollectionTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $result = new ArrayObject();

        foreach ($paymentCrefoPayOrderItemEntities as $paymentCrefoPayOrderItemEntity) {
            $paymentCrefoPayOrderItemTransfer = $this->mapEntityToPaymentCrefoPayOrderItemTransfer(
                $paymentCrefoPayOrderItemEntity,
                new PaymentCrefoPayOrderItemTransfer()
            );
            $result->append($paymentCrefoPayOrderItemTransfer);
        }

        return $crefoPayOrderItemCollectionTransfer
            ->setCrefoPayOrderItems($result);
    }

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPay $paymentCrefoPayEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function mapEntityToPaymentCrefoPayTransfer(
        SpyPaymentCrefoPay $paymentCrefoPayEntity,
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer
    ): PaymentCrefoPayTransfer {
        $paymentCrefoPayTransfer->fromArray(
            $paymentCrefoPayEntity->toArray(),
            true
        );

        return $paymentCrefoPayTransfer;
    }

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem $paymentCrefoPayOrderItemEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    public function mapEntityToPaymentCrefoPayOrderItemTransfer(
        SpyPaymentCrefoPayOrderItem $paymentCrefoPayOrderItemEntity,
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
    ): PaymentCrefoPayOrderItemTransfer {
        $paymentCrefoPayOrderItemTransfer->fromArray(
            $paymentCrefoPayOrderItemEntity->toArray(),
            true
        );

        return $paymentCrefoPayOrderItemTransfer;
    }

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotification $paymentCrefoPayNotificationEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer
     */
    public function mapEntityToPaymentCrefoPayNotificationTransfer(
        SpyPaymentCrefoPayNotification $paymentCrefoPayNotificationEntity,
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
    ): PaymentCrefoPayNotificationTransfer {
        $paymentCrefoPayNotificationTransfer->fromArray(
            $paymentCrefoPayNotificationEntity->toArray(),
            true
        );

        return $paymentCrefoPayNotificationTransfer;
    }

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLog $paymentCrefoPayOrderItemToCrefoPayApiLogEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    public function mapEntityToPaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(
        SpyPaymentCrefoPayOrderItemToCrefoPayApiLog $paymentCrefoPayOrderItemToCrefoPayApiLogEntity,
        PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer->fromArray(
            $paymentCrefoPayOrderItemToCrefoPayApiLogEntity->toArray(),
            true
        );

        return $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
    }

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotification $paymentCrefoPayOrderItemToCrefoPayNotificationEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
        SpyPaymentCrefoPayOrderItemToCrefoPayNotification $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
        PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer->fromArray(
            $paymentCrefoPayOrderItemToCrefoPayNotificationEntity->toArray(),
            true
        );

        return $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
    }
}
