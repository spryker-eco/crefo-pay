<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Processor;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use SprykerEco\Zed\CrefoPay\Business\Processor\Mapper\CrefoPayNotificationStatusMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;

class CrefoPayNotificationProcessor implements CrefoPayNotificationProcessorInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Processor\Mapper\CrefoPayNotificationStatusMapperInterface
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
     * @param \SprykerEco\Zed\CrefoPay\Business\Processor\Mapper\CrefoPayNotificationStatusMapperInterface $statusMapper
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     */
    public function __construct(
        CrefoPayNotificationStatusMapperInterface $statusMapper,
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
        $this->writer->saveNotification($notificationTransfer);
        $this->updateCrefoPayOrderItemStatuses($notificationTransfer);

        return $notificationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return void
     */
    protected function updateCrefoPayOrderItemStatuses(CrefoPayNotificationTransfer $notificationTransfer): void
    {
        $status = $this->getOmsStatus($notificationTransfer);
        if ($status === null) {
            return;
        }

        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findAllPaymentCrefoPayOrderItemsByCrefoPayOrderId($notificationTransfer->getOrderID());

        $this->writer->updatePaymentEntities(
            $status,
            $paymentCrefoPayOrderItemCollection
        );
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
