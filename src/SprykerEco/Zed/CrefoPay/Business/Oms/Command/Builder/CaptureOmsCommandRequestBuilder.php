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
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    public function buildRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        $captureRequestTransfer = $this->createCaptureRequestTransfer($crefoPayOmsCommandTransfer);
        $amountToCapture = $this->getOrderItemsAmount($crefoPayOmsCommandTransfer);
        $captureRequestTransfer->setAmount($this->createAmountTransfer($amountToCapture));
        $requestTransfer = (new CrefoPayApiRequestTransfer())
            ->setCaptureRequest($captureRequestTransfer);

        $crefoPayOmsCommandTransfer->setRequest($requestTransfer);

        return $this->addExpensesRequest($crefoPayOmsCommandTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiCaptureRequestTransfer
     */
    protected function createCaptureRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiCaptureRequestTransfer
    {
        return (new CrefoPayApiCaptureRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId())
            ->setCaptureID(
                $this->utilTextService->generateRandomString(
                    $this->config->getCrefoPayApiCaptureIdLength()
                )
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isFirstCapture(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        return $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCapturedAmount() === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function addExpensesRequest(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        if ($this->config->getCaptureExpensesSeparately() && !$this->isFirstCapture($crefoPayOmsCommandTransfer)) {
            return $crefoPayOmsCommandTransfer;
        }

        $expensesAmountToCapture = $this->calculateExpensesAmount($crefoPayOmsCommandTransfer->getOrder());

        if ($expensesAmountToCapture <= 0) {
            return $crefoPayOmsCommandTransfer;
        }

        if (!$this->config->getCaptureExpensesSeparately()) {
            $orderItemsAmountToCapture = $crefoPayOmsCommandTransfer
                ->getRequest()
                ->getCaptureRequest()
                ->getAmount()
                ->getAmount();

            $crefoPayOmsCommandTransfer
                ->getRequest()
                ->getCaptureRequest()
                ->getAmount()
                ->setAmount($orderItemsAmountToCapture + $expensesAmountToCapture);

            return $crefoPayOmsCommandTransfer;
        }

        $captureExpenseRequestTransfer = $this->createCaptureRequestTransfer($crefoPayOmsCommandTransfer);
        $captureExpenseRequestTransfer->setAmount($this->createAmountTransfer($expensesAmountToCapture));
        $expensesRequestTransfer = (new CrefoPayApiRequestTransfer())
            ->setCaptureRequest($captureExpenseRequestTransfer);

        return $crefoPayOmsCommandTransfer
            ->setExpensesRequest($expensesRequestTransfer);
    }

    /**
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAmountTransfer
     */
    protected function createAmountTransfer(int $amount): CrefoPayApiAmountTransfer
    {
        return (new CrefoPayApiAmountTransfer())
            ->setAmount($amount);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return int
     */
    protected function getOrderItemsAmount(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): int
    {
        return (int)array_sum(
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
