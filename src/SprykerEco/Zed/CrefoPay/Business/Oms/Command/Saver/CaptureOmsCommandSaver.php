<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface;

class CaptureOmsCommandSaver implements CrefoPayOmsCommandSaverInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface
     */
    protected $reader;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface
     */
    protected $writer;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface
     */
    protected $omsFacade;

    /**
     * @var \SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface
     */
    protected $statusMapper;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface $omsFacade
     * @param \SprykerEco\Zed\CrefoPay\Business\Oms\Mapper\CrefoPayOmsStatusMapperInterface $statusMapper
     */
    public function __construct(
        CrefoPayReaderInterface $reader,
        CrefoPayWriterInterface $writer,
        CrefoPayConfig $config,
        CrefoPayToOmsFacadeInterface $omsFacade,
        CrefoPayOmsStatusMapperInterface $statusMapper
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->config = $config;
        $this->omsFacade = $omsFacade;
        $this->statusMapper = $statusMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return void
     */
    public function savePaymentEntities(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): void
    {
        if ($crefoPayOmsCommandTransfer->getResponse()->getIsSuccess() === false) {
            return;
        }

        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayOrderId(
                $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId()
            );

        $paymentCrefoPayOrderItemsToCapture = $this->getPaymentCrefoPayOrderItemsToCapture(
            $crefoPayOmsCommandTransfer,
            $paymentCrefoPayOrderItemCollection
        );

        $isFirstCommandExecution = $this->isFirstCommandExecution($crefoPayOmsCommandTransfer);

        $this->writer->updatePaymentEntities(
            $paymentCrefoPayOrderItemsToCapture,
            $this->getPaymentCrefoPay($crefoPayOmsCommandTransfer),
            $crefoPayOmsCommandTransfer->getResponse()->getCrefoPayApiLogId()
        );

        if (!$this->isSingleOrderItemCommandExecution($crefoPayOmsCommandTransfer) || !$isFirstCommandExecution) {
            return;
        }

        $affectedSalesOrderItemIds = $this->getAffectedSalesOrderItemIds(
            $crefoPayOmsCommandTransfer,
            $paymentCrefoPayOrderItemCollection
        );

        $this->omsFacade->triggerEventForOrderItems(
            $this->config->getOmsEventNoCancellation(),
            $affectedSalesOrderItemIds,
            [$this->config->getCrefoPayAutomaticOmsTrigger()]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function getPaymentCrefoPayOrderItemsToCapture(
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer,
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): PaymentCrefoPayOrderItemCollectionTransfer {

        $status = $this->statusMapper
            ->mapNotificationOrderStatusToOmsStatus(
                $crefoPayOmsCommandTransfer->getResponse()->getCaptureResponse()->getStatus()
            );
        $captureId = $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest()->getCaptureID();

        $salesOrderItemIds = array_map(
            function (CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItemTransfer) {
                return $crefoPayToSalesOrderItemTransfer->getIdSalesOrderItem();
            },
            $crefoPayOmsCommandTransfer
                ->getCrefoPayToSalesOrderItemsCollection()
                ->getCrefoPayToSalesOrderItems()
                ->getArrayCopy()
        );

        $paymentCrefoPayOrderItems = array_filter(
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy(),
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItem) use ($salesOrderItemIds) {
                return in_array($paymentCrefoPayOrderItem->getFkSalesOrderItem(), $salesOrderItemIds);
            }
        );

        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status, $captureId) {
                return $paymentCrefoPayOrderItemTransfer
                    ->setStatus($status)
                    ->setCaptureId($captureId);
            },
            $paymentCrefoPayOrderItems
        );

        return (new PaymentCrefoPayOrderItemCollectionTransfer())
            ->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function getPaymentCrefoPay(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayTransfer = $crefoPayOmsCommandTransfer->getPaymentCrefoPay();
        $capturedAmount = $paymentCrefoPayTransfer->getCapturedAmount();
        $requestedAmount = $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest()->getAmount()->getAmount();

        return $paymentCrefoPayTransfer
            ->setCapturedAmount($capturedAmount + $requestedAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isSingleOrderItemCommandExecution(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        $crefoPayToSalesOrderItemsCollection = $crefoPayOmsCommandTransfer->getCrefoPayToSalesOrderItemsCollection();

        return $crefoPayToSalesOrderItemsCollection->getCrefoPayToSalesOrderItems()->count() === 1;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isFirstCommandExecution(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        return $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCapturedAmount() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return int[]
     */
    protected function getAffectedSalesOrderItemIds(
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer,
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): array {
        /** @var \Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItem */
        $crefoPayToSalesOrderItem = $crefoPayOmsCommandTransfer
            ->getCrefoPayToSalesOrderItemsCollection()
            ->getCrefoPayToSalesOrderItems()
            ->offsetGet(0);

        $affectedSalesOrderItemIds = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                return $paymentCrefoPayOrderItemTransfer->getFkSalesOrderItem();
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        $key = array_search($crefoPayToSalesOrderItem->getIdSalesOrderItem(), $affectedSalesOrderItemIds);
        if ($key !== false) {
            unset($affectedSalesOrderItemIds[$key]);
        }

        return $affectedSalesOrderItemIds;
    }
}
