<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Saver;

use ArrayObject;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemCollectionTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus\CrefoPayOmsStatusMapperInterface;
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
     * @var \SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus\CrefoPayOmsStatusMapperInterface
     */
    protected $statusMapper;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Business\Reader\CrefoPayReaderInterface $reader
     * @param \SprykerEco\Zed\CrefoPay\Business\Writer\CrefoPayWriterInterface $writer
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeInterface $omsFacade
     * @param \SprykerEco\Zed\CrefoPay\Business\Mapper\OmsStatus\CrefoPayOmsStatusMapperInterface $statusMapper
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

        $this->writer->updatePaymentEntities(
            $this->getPaymentCrefoPayOrderItemsToCapture($crefoPayOmsCommandTransfer),
            $this->getPaymentCrefoPayTransfer($crefoPayOmsCommandTransfer),
            $crefoPayOmsCommandTransfer->getResponse()->getCrefoPayApiLogId()
        );

        $this->triggerRemainingOrderItems($crefoPayOmsCommandTransfer);
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

        $paymentCrefoPayOrderItems = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) use ($status, $captureId) {
                return $paymentCrefoPayOrderItemTransfer
                    ->setStatus($status)
                    ->setCaptureId($captureId);
            },
            $crefoPayOmsCommandTransfer
                ->getPaymentCrefoPayOrderItemCollection()
                ->getCrefoPayOrderItems()
                ->getArrayCopy()
        );

        return $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPayOrderItemCollection()
            ->setCrefoPayOrderItems(
                new ArrayObject($paymentCrefoPayOrderItems)
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCrefoPayTransfer
     */
    protected function getPaymentCrefoPayTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): PaymentCrefoPayTransfer
    {
        $paymentCrefoPayTransfer = $crefoPayOmsCommandTransfer->getPaymentCrefoPay();
        $captureRequest = $crefoPayOmsCommandTransfer->getRequest()->getCaptureRequest();
        $capturedAmount = $paymentCrefoPayTransfer->getCapturedAmount();

        if ($this->isExpensesCaptureRequestPerformedSuccessfully($crefoPayOmsCommandTransfer)) {
            $expensesCaptureRequest = $crefoPayOmsCommandTransfer->getExpensesRequest()->getCaptureRequest();
            $paymentCrefoPayTransfer
                ->setExpensesCaptureId($expensesCaptureRequest->getCaptureID())
                ->setExpensesCapturedAmount($expensesCaptureRequest->getAmount()->getAmount());

            $capturedAmount += $expensesCaptureRequest->getAmount()->getAmount();
        }

        if ($capturedAmount === 0 && !$this->config->getCaptureExpensesSeparately()) {
            $paymentCrefoPayTransfer
                ->setExpensesCaptureId($captureRequest->getCaptureID())
                ->setExpensesCapturedAmount(
                    $this->calculateExpensesAmount(
                        $crefoPayOmsCommandTransfer->getOrder()
                    )
                );
        }

        $capturedAmount += $captureRequest->getAmount()->getAmount();

        return $paymentCrefoPayTransfer
            ->setCapturedAmount($capturedAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return void
     */
    protected function triggerRemainingOrderItems(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): void
    {
        $remainingOrderItems = $this->getRemainingOrderItems($crefoPayOmsCommandTransfer);

        if (count($remainingOrderItems) === 0) {
            return;
        }

        $affectedSalesOrderItemIds = array_map(
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                return $paymentCrefoPayOrderItemTransfer->getIdSalesOrderItem();
            },
            $remainingOrderItems
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
     * @return \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer[]
     */
    protected function getRemainingOrderItems(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): array
    {
        $paymentCrefoPayOrderItemCollection = $this->reader
            ->getPaymentCrefoPayOrderItemCollectionByCrefoPayOrderId(
                $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId()
            );

        return array_filter(
            $paymentCrefoPayOrderItemCollection->getCrefoPayOrderItems()->getArrayCopy(),
            function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                return $paymentCrefoPayOrderItemTransfer->getStatus() === $this->config->getOmsStatusWaitingForCapture();
            }
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isExpensesCaptureRequestPerformedSuccessfully(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        return $crefoPayOmsCommandTransfer->getExpensesResponse() !== null && $crefoPayOmsCommandTransfer->getExpensesResponse()->getIsSuccess();
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function calculateExpensesAmount(OrderTransfer $orderTransfer): int
    {
        return (int)array_sum(
            array_map(
                function (ExpenseTransfer $expense) {
                    return $expense->getSumPriceToPayAggregation();
                },
                $orderTransfer->getExpenses()->getArrayCopy()
            )
        );
    }
}
