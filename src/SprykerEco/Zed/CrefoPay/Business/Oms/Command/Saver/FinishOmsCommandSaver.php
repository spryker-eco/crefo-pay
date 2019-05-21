<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface;
use SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface;

class FinishOmsCommandSaver implements CrefoPayOmsCommandSaverInterface
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
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface $omsFacade
     */
    public function __construct(
        CrefoPayReaderInterface $reader,
        CrefoPayWriterInterface $writer,
        CrefoPayConfig $config,
        CrefoPayToOmsFacadeInterface $omsFacade
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->config = $config;
        $this->omsFacade = $omsFacade;
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
        $paymentCrefoPayOrderItemCollection = $this->getPaymentCrefoPayOrderItemCollection($crefoPayOmsCommandTransfer);
        $this->writer->updatePaymentEntities(
            $paymentCrefoPayOrderItemCollection,
            null,
            $crefoPayOmsCommandTransfer->getResponse()->getCrefoPayApiLogId()
        );

        if (!$this->isSingleOrderItemCommandExecution($crefoPayOmsCommandTransfer)) {
            return;
        }

        $affectedSalesOrderItemIds = $this->getAffectedSalesOrderItemIds(
            $crefoPayOmsCommandTransfer,
            $paymentCrefoPayOrderItemCollection
        );

        $this->omsFacade->triggerEventForOrderItems(
            $this->config->getOmsEventFinish(),
            $affectedSalesOrderItemIds,
            [$this->config->getCrefoPayAutomaticOmsTrigger()]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer
     */
    protected function getPaymentCrefoPayOrderItemCollection(
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
    ): PaymentCrefoPayOrderItemCollectionTransfer {
        $status = $this->config->getOmsStatusMoneyReduced();
        $paymentCrefoPayOrderItemCollection = $this->reader
            ->findPaymentCrefoPayOrderItemsByCrefoPayOrderId(
                $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId()
            );
        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status) {
                return $paymentCrefoPayOrderItemTransfer->setStatus($status);
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        return $paymentCrefoPayOrderItemCollection
            ->setCrefoPayOrderItems(new ArrayObject($paymentCrefoPayOrderItems));
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
     * @param \Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
     *
     * @return int[]
     */
    protected function getAffectedSalesOrderItemIds(
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer,
        PaymentCrefoPayOrderItemCollectionTransfer $paymentCrefoPayOrderItemCollection
    ): array {
        $affectedSalesOrderItemIds = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                return $paymentCrefoPayOrderItemTransfer->getFkSalesOrderItem();
            },
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy()
        );

        /** @var \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer */
        $paymentCrefoPayOrderItemTransfer = $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPayOrderItemCollection()
            ->getCrefoPayOrderItems()
            ->offsetGet(0);

        $key = array_search($paymentCrefoPayOrderItemTransfer->getFkSalesOrderItem(), $affectedSalesOrderItemIds);
        if ($key !== false) {
            unset($affectedSalesOrderItemIds[$key]);
        }

        return $affectedSalesOrderItemIds;
    }
}
