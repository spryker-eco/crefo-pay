<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiAmountTransfer;
use Generated\Shared\Transfer\CrefoPayApiCaptureRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CaptureOmsCommandRequestBuilder implements CrefoPayOmsCommandRequestBuilderInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $utilTextService
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(
        CrefoPayToUtilTextServiceInterface $utilTextService,
        CrefoPayConfig $config
    ) {
        $this->utilTextService = $utilTextService;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRequestTransfer
     */
    public function buildRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiRequestTransfer
    {
        $captureRequestTransfer = $this->createCaptureRequestTransfer(
            $crefoPayOmsCommandTransfer->getPaymentCrefoPay()
        );
        $captureRequestTransfer->setAmount(
            $this->createAmountTransfer($crefoPayOmsCommandTransfer)
        );

        return (new CrefoPayApiRequestTransfer())
            ->setCaptureRequest($captureRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiCaptureRequestTransfer
     */
    protected function createCaptureRequestTransfer(PaymentCrefoPayTransfer $paymentCrefoPayTransfer): CrefoPayApiCaptureRequestTransfer
    {
        return (new CrefoPayApiCaptureRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($paymentCrefoPayTransfer->getCrefoPayOrderId())
            ->setCaptureID(
                $this->utilTextService->generateRandomString(
                    $this->config->getCrefoPayApiCaptureIdLength()
                )
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAmountTransfer
     */
    protected function createAmountTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiAmountTransfer
    {
        $amountTransfer = new CrefoPayApiAmountTransfer();

        if ($this->isFullCapture($crefoPayOmsCommandTransfer)) {
            return $amountTransfer
                ->setAmount(
                    $crefoPayOmsCommandTransfer->getOrder()->getTotals()->getGrandTotal()
                );
        }

        $captureAmount = $this->calculateOrderItemsAmount($crefoPayOmsCommandTransfer);
        if (!$crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCapturedAmount()) {
            $captureAmount += $this->calculateExpensesAmount(
                $crefoPayOmsCommandTransfer->getOrder()
            );
        }

        return $amountTransfer
            ->setAmount($captureAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isFullCapture(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        $orderItems = $crefoPayOmsCommandTransfer->getOrder()->getItems();
        $paymentCrefoPayOrderItemCollection = $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPayOrderItemCollection()
            ->getCrefoPayOrderItems();


        return $orderItems->count() === $paymentCrefoPayOrderItemCollection->count();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return bool
     */
    protected function isFirstCapture(PaymentCrefoPayTransfer $paymentCrefoPayTransfer): bool
    {
        return $paymentCrefoPayTransfer->getCapturedAmount() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return int
     */
    protected function calculateOrderItemsAmount(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): int
    {
        return array_sum(
            array_map(
                function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                    return $paymentCrefoPayOrderItemTransfer->getAmount();
                },
                $crefoPayOmsCommandTransfer
                    ->getPaymentCrefoPayOrderItemCollection()
                    ->getCrefoPayOrderItems()
                    ->getArrayCopy()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function calculateExpensesAmount(OrderTransfer $orderTransfer): int
    {
        return array_sum(
            array_map(
                function (ExpenseTransfer $expense) {
                    return $expense->getSumPriceToPayAggregation();
                },
                $orderTransfer->getExpenses()->getArrayCopy()
            )
        );
    }
}
