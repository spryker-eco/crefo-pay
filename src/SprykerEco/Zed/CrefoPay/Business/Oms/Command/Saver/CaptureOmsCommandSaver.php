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

        $isFirstCommandExecution = $this->isFirstCommandExecution($crefoPayOmsCommandTransfer);

        $this->writer->updatePaymentEntities(
            $this->getPaymentCrefoPayOrderItemsToCapture($crefoPayOmsCommandTransfer),
            $this->getPaymentCrefoPayTransfer($crefoPayOmsCommandTransfer),
            $crefoPayOmsCommandTransfer->getResponse()->getCrefoPayApiLogId()
        );

        if (!$this->isSingleOrderItemCommandExecution($crefoPayOmsCommandTransfer) || !$isFirstCommandExecution) {
            return;
        }

        $this->omsFacade->triggerEventForOrderItems(
            $this->config->getOmsEventNoCancellation(),
            $this->getAffectedSalesOrderItemIds($crefoPayOmsCommandTransfer),
            [$this->config->getCrefoPayAutomaticOmsTrigger()]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function getPaymentCrefoPayOrderItemsToCapture(
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {

        $status = $this->statusMapper
            ->mapNotificationOrderStatusToOmsStatus(
                $crefoPayOmsCommandTransfer->getResponse()->getCaptureResponse()->getStatus()
            );
        $captureId = $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest()->getCaptureID();
        $paymentCrefoPayOrderItemCollection = $crefoPayOmsCommandTransfer->getPaymentCrefoPayOrderItemCollection();

        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status, $captureId) {
                return $paymentCrefoPayOrderItemTransfer
                    ->setStatus($status)
                    ->setCaptureId($captureId);
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        return $paymentCrefoPayOrderItemCollection
            ->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function getPaymentCrefoPayTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayTransfer = $crefoPayOmsCommandTransfer->getPaymentCrefoPay();
        $capturedAmount = $paymentCrefoPayTransfer->getCapturedAmount();
        $capturedAmount += $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest()->getAmount()->getAmount();

        if ($crefoPayOmsCommandTransfer->getExpensesResponse() && $crefoPayOmsCommandTransfer->getExpensesResponse()->getIsSuccess()) {
            $capturedAmount += $crefoPayOmsCommandTransfer->getExpensesRequest()->getCaptureRequest()->getAmount()->getAmount();
        }

        return $paymentCrefoPayTransfer
            ->setCapturedAmount($capturedAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isSingleOrderItemCommandExecution(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        $paymentCrefoPayOrderItemCollection = $crefoPayOmsCommandTransfer->getPaymentCrefoPayOrderItemCollection();

        return $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->count() === 1;
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
     *
     * @return int[]
     */
    protected function getAffectedSalesOrderItemIds(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): array
    {
        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayOrderIdAndCaptureId(
                $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId()
            );

        /** @var \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer */
        $paymentCrefoPayOrderItemTransfer = $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPayOrderItemCollection()
            ->getCrefoPayOrderItems()
            ->offsetGet(0);

        $affectedSalesOrderItemIds = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                return $paymentCrefoPayOrderItemTransfer->getFkSalesOrderItem();
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        $key = array_search($paymentCrefoPayOrderItemTransfer->getFkSalesOrderItem(), $affectedSalesOrderItemIds);
        if ($key !== false) {
            unset($affectedSalesOrderItemIds[$key]);
        }

        return $affectedSalesOrderItemIds;
    }
}
