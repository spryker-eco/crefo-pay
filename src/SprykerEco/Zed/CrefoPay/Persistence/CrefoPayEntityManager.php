<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\Persistence\CrefoPayPersistenceFactory getFactory()
 */
class CrefoPayEntityManager extends AbstractEntityManager implements CrefoPayEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    public function savePaymentCrefoPayEntity(PaymentCrefoPayTransfer $paymentCrefoPayTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayEntity = $this->getFactory()
            ->createPaymentCrefoPayQuery()
            ->filterByFkSalesOrder($paymentCrefoPayTransfer->getFkSalesOrder())
            ->filterByCrefoPayOrderId($paymentCrefoPayTransfer->getCrefoPayOrderId())
            ->findOneOrCreate();

        $paymentCrefoPayEntity->fromArray(
            $paymentCrefoPayTransfer->modifiedToArray()
        );
            $paymentCrefoPayEntity->save();

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayTransfer(
                $paymentCrefoPayEntity,
                $paymentCrefoPayTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    public function savePaymentCrefoPayOrderItemEntity(
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
    ): PaymentCrefoPayOrderItemTransfer {
        $paymentCrefoPayOrderItemEntity = $this->getFactory()
            ->createPaymentCrefoPayOrderItemQuery()
            ->filterByFkSalesOrderItem($paymentCrefoPayOrderItemTransfer->getFkSalesOrderItem())
            ->filterByFkPaymentCrefoPay($paymentCrefoPayOrderItemTransfer->getFkPaymentCrefoPay())
            ->findOneOrCreate();

        $paymentCrefoPayOrderItemEntity->fromArray(
            $paymentCrefoPayOrderItemTransfer->modifiedToArray()
        );
        $paymentCrefoPayOrderItemEntity->save();

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemTransfer(
                $paymentCrefoPayOrderItemEntity,
                $paymentCrefoPayOrderItemTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer
     */
    public function savePaymentCrefoPayNotificationEntity(
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
    ): PaymentCrefoPayNotificationTransfer {
        $paymentCrefoPayNotificationEntity = $this->getFactory()
            ->createPaymentCrefoPayNotificationQuery()
            ->filterByIdPaymentCrefoPayNotification(
                $paymentCrefoPayNotificationTransfer->getIdPaymentCrefoPayNotification()
            )
            ->findOneOrCreate();

        $paymentCrefoPayNotificationEntity->fromArray(
            $paymentCrefoPayNotificationTransfer->modifiedToArray()
        );
        $paymentCrefoPayNotificationEntity->save();

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayNotificationTransfer(
                $paymentCrefoPayNotificationEntity,
                $paymentCrefoPayNotificationTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer
     */
    public function savePaymentCrefoPayOrderItemToCrefoPayApiLogEntity(
        PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
    ): PaymentCrefoPayOrderItemToCrefoPayApiLogTransfer {
        $paymentCrefoPayOrderItemToCrefoPayApiLogEntity = $this->getFactory()
            ->createPaymentCrefoPayOrderItemToCrefoPayApiLogQuery()
            ->filterByFkPaymentCrefoPayApiLog(
                $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer->getFkPaymentCrefoPayApiLog()
            )
            ->filterByFkPaymentCrefoPayOrderItem(
                $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer->getFkPaymentCrefoPayOrderItem()
            )
            ->findOneOrCreate();

        $paymentCrefoPayOrderItemToCrefoPayApiLogEntity->fromArray(
            $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer->modifiedToArray()
        );
        $paymentCrefoPayOrderItemToCrefoPayApiLogEntity->save();

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayApiLogTransfer(
                $paymentCrefoPayOrderItemToCrefoPayApiLogEntity,
                $paymentCrefoPayOrderItemToCrefoPayApiLogTransfer
            );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer
     */
    public function savePaymentCrefoPayOrderItemToCrefoPayNotificationEntity(
        PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
    ): PaymentCrefoPayOrderItemToCrefoPayNotificationTransfer {
        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity = $this->getFactory()
            ->createPaymentCrefoPayOrderItemToCrefoPayNotificationQuery()
            ->filterByFkPaymentCrefoPayNotification(
                $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer->getFkPaymentCrefoPayNotification()
            )
            ->filterByFkPaymentCrefoPayOrderItem(
                $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer->getFkPaymentCrefoPayOrderItem()
            )
            ->findOneOrCreate();

        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity->fromArray(
            $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer->modifiedToArray()
        );
        $paymentCrefoPayOrderItemToCrefoPayNotificationEntity->save();

        return $this->getMapper()
            ->mapEntityToPaymentCrefoPayOrderItemToCrefoPayNotificationTransfer(
                $paymentCrefoPayOrderItemToCrefoPayNotificationEntity,
                $paymentCrefoPayOrderItemToCrefoPayNotificationTransfer
            );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface
     */
    protected function getMapper(): CrefoPayPersistenceMapperInterface
    {
        return $this->getFactory()->createCrefoPayPersistenceMapper();
    }
}
