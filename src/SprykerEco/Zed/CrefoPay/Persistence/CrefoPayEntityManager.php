<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Persistence;

use Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer;
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
    public function savePaymentCrefoPay(PaymentCrefoPayTransfer $paymentCrefoPayTransfer): PaymentCrefoPayTransfer
    {
        $entityTransfer = $this->getMapper()
            ->mapPaymentCrefoPayTransferToEntityTransfer($paymentCrefoPayTransfer);

        /** @var \Generated\Shared\Transfer\SpyPaymentCrefoPayEntityTransfer $entityTransfer */
        $entityTransfer = $this->save($entityTransfer);

        $paymentCrefoPayTransfer = $this->getMapper()
            ->mapEntityTransferToPaymentCrefoPayTransfer(
                $entityTransfer,
                $paymentCrefoPayTransfer
            );

        return $paymentCrefoPayTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer
     */
    public function savePaymentCrefoPayOrderItem(
        PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer
    ): PaymentCrefoPayOrderItemTransfer {
        $entityTransfer = $this->getMapper()
            ->mapPaymentCrefoPayOrderItemTransferToEntityTransfer($paymentCrefoPayOrderItemTransfer);

        /** @var \Generated\Shared\Transfer\SpyPaymentCrefoPayOrderItemEntityTransfer $entityTransfer */
        $entityTransfer = $this->save($entityTransfer);

        $paymentCrefoPayOrderItemTransfer = $this->getMapper()
            ->mapEntityTransferToPaymentCrefoPayOrderItemTransfer(
                $entityTransfer,
                $paymentCrefoPayOrderItemTransfer
            );

        return $paymentCrefoPayOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer $paymentCrefoPayApiLogTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayApiLogTransfer
     */
    public function savePaymentCrefoPayApiLog(
        PaymentCrefoPayApiLogTransfer $paymentCrefoPayApiLogTransfer
    ): PaymentCrefoPayApiLogTransfer {
        $entityTransfer = $this->getMapper()
            ->mapPaymentCrefoPayApiLogTransferToEntityTransfer($paymentCrefoPayApiLogTransfer);

        /** @var \Generated\Shared\Transfer\SpyPaymentCrefoPayApiLogEntityTransfer $entityTransfer */
        $entityTransfer = $this->save($entityTransfer);

        $paymentCrefoPayApiLogTransfer = $this->getMapper()
            ->mapEntityTransferToPaymentCrefoPayApiLogTransfer(
                $entityTransfer,
                $paymentCrefoPayApiLogTransfer
            );

        return $paymentCrefoPayApiLogTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayNotificationTransfer
     */
    public function savePaymentCrefoPayNotification(
        PaymentCrefoPayNotificationTransfer $paymentCrefoPayNotificationTransfer
    ): PaymentCrefoPayNotificationTransfer {
        $entityTransfer = $this->getMapper()
            ->mapPaymentCrefoPayNotificationTransferToEntityTransfer($paymentCrefoPayNotificationTransfer);

        /** @var \Generated\Shared\Transfer\SpyPaymentCrefoPayNotificationEntityTransfer $entityTransfer */
        $entityTransfer = $this->save($entityTransfer);

        $paymentCrefoPayNotificationTransfer = $this->getMapper()
            ->mapEntityTransferToPaymentCrefoPayNotificationTransfer(
                $entityTransfer,
                $paymentCrefoPayNotificationTransfer
            );

        return $paymentCrefoPayNotificationTransfer;
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Persistence\Mapper\CrefoPayPersistenceMapperInterface
     */
    protected function getMapper(): CrefoPayPersistenceMapperInterface
    {
        return $this->getFactory()->createCrefoPayPersistenceMapper();
    }
}
