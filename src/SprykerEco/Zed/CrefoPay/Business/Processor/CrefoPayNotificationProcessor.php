<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Processor;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus\CrefoPayOmsStatusMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;

class CrefoPayNotificationProcessor implements CrefoPayNotificationProcessorInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus\CrefoPayOmsStatusMapperInterface
     */
    protected $statusMapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface
     */
    protected $writer;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus\CrefoPayOmsStatusMapperInterface $statusMapper
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     */
    public function __construct(
        CrefoPayOmsStatusMapperInterface $statusMapper,
        CrefoPayReaderInterface $reader,
        CrefoPayWriterInterface $writer
    ) {
        $this->statusMapper = $statusMapper;
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    public function processNotification(CrefoPayNotificationTransfer $notificationTransfer): CrefoPayNotificationTransfer
    {
        $status = $this->getOmsStatus($notificationTransfer);
        $paymentCrefoPayOrderItemsCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayOrderIdAndCaptureId(
                $notificationTransfer->getOrderID(),
                $notificationTransfer->getCaptureID()

            );

        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status) {
                return $paymentCrefoPayOrderItemTransfer->setStatus($status);
            },
            $paymentCrefoPayOrderItemsCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        $paymentCrefoPayOrderItemsCollection->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));

        $this->writer->createNotificationEntities(
            $notificationTransfer,
            $paymentCrefoPayOrderItemsCollection
        );

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return string|null
     */
    protected function getOmsStatus(CrefoPayNotificationTransfer $notificationTransfer): ?string
    {
        if (!empty($notificationTransfer->getTransactionStatus())) {
            $status = $this->statusMapper
                ->mapNotificationTransactionStatusToOmsStatus(
                    $notificationTransfer->getTransactionStatus()
                );
        }

        if (!empty($notificationTransfer->getOrderStatus())) {
            $status = $this->statusMapper
                ->mapNotificationOrderStatusToOmsStatus(
                    $notificationTransfer->getOrderStatus()
                );
        }

        return $status ?? null;
    }
}
