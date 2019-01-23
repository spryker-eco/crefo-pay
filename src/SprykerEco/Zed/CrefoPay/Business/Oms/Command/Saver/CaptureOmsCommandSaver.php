<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use ArrayObject;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer;
use SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CaptureOmsCommandSaver implements CrefoPayOmsCommandSaverInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface
     */
    protected $writer;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface
     */
    protected $statusMapper;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface $statusMapper
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(
        CrefoPayWriterInterface $writer,
        CrefoPayOmsStatusMapperInterface $statusMapper,
        CrefoPayConfig $config
    ) {
        $this->writer = $writer;
        $this->statusMapper = $statusMapper;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer
     *
     * @return void
     */
    public function savePaymentEntities(SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer): void
    {
        if ($saveCrefoPayEntitiesTransfer->getResponse()->getIsSuccess() === false) {
            return;
        }

        $this->writer->updatePaymentEntities(
            $this->getPaymentCrefoPayOrderItemCollection($saveCrefoPayEntitiesTransfer),
            $this->getPaymentCrefoPay($saveCrefoPayEntitiesTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function getPaymentCrefoPayOrderItemCollection(
        SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {

        $status = $this->statusMapper
            ->mapNotificationOrderStatusToOmsStatus(
                $saveCrefoPayEntitiesTransfer->getResponse()->getCaptureResponse()->getStatus()
            );
        $captureId = $saveCrefoPayEntitiesTransfer->getRequest()->getCaptureRequest()->getCaptureID();
        $paymentCrefoPayOrderItemCollectionTransfer = $saveCrefoPayEntitiesTransfer->getPaymentCrefoPayOrderItemCollection();
        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status, $captureId) {
                return $paymentCrefoPayOrderItemTransfer
                    ->setStatus($status)
                    ->setCaptureId($captureId);
            },
            $paymentCrefoPayOrderItemCollectionTransfer->getCrefoPayOrderItems()->getArrayCopy()
        );

        return $paymentCrefoPayOrderItemCollectionTransfer
            ->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));
    }

    /**
     * @param \Generated\Shared\Transfer\SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function getPaymentCrefoPay(SaveCrefoPayEntitiesTransfer $saveCrefoPayEntitiesTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayTransfer = $saveCrefoPayEntitiesTransfer->getPaymentCrefoPay();
        $capturedAmount = $paymentCrefoPayTransfer->getCapturedAmount();
        $requestedAmount = $saveCrefoPayEntitiesTransfer->getRequest()->getCaptureRequest()->getAmount()->getAmount();

        return $paymentCrefoPayTransfer
            ->setCapturedAmount($capturedAmount + $requestedAmount);
    }
}
