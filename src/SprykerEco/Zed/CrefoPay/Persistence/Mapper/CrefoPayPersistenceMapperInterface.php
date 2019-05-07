<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence\Mapper;

use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPay;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotification;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLog;
use Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotification;

interface CrefoPayPersistenceMapperInterface
{
    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPay $paymentCrefoPayEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function mapEntityToPaymentCrefoPayTransfer(
        SpyPaymentCrefoPay $paymentCrefoPayEntity,
        PaymentCrefoPayTransfer $paymentCrefoPayTransfer
    ): PaymentCrefoPayTransfer;

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItem $paymentCrefoPayOrderItemEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    public function mapEntityToPaymentCrefoPayOrderItemTransfer(
        SpyPaymentCrefoPayOrderItem $paymentCrefoPayOrderItemEntity,
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
    ): PaymentCrefoPayOrderItemTransfer;

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayNotification $paymentCrefoPayNotificationEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer
     */
    public function mapEntityToPaymentCrefoPayNotificationTransfer(
        SpyPaymentCrefoPayNotification $paymentCrefoPayNotificationEntity,
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
    ): PaymentCrefoPayNotificationTransfer;

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayApiLog $paymentCrefoPayOrderItemToCrefoPayApiLogEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    public function mapEntityToPaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(
        SpyPaymentCrefoPayOrderItemToCrefoPayApiLog $paymentCrefoPayOrderItemToCrefoPayApiLogEntity,
        PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;

    /**
     * @param \Orm\Zed\CrefoPay\Persistence\SpyPaymentCrefoPayOrderItemToCrefoPayNotification $paymentCrefoPayOrderItemToCrefoPayNotificationEntity
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
        SpyPaymentCrefoPayOrderItemToCrefoPayNotification $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
        PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
}
