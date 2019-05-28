<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiAmountTransfer;
use Generated\Shared\Transfer\CrefoPayApiRefundRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer;
use SprykerEco\Zed\CrefoPay\Business\Exception\InvalidItemsToRefundAggregationException;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class RefundOmsCommandRequestBuilder implements CrefoPayOmsCommandRequestBuilderInterface
{
    protected const INVALID_ITEMS_AGGREGATION_MESSAGE = 'Order items to refund have to have same captureId.';

    /**
     * @var \SprykerEco\Zed\CrefoPay\CrefoPayConfig
     */
    protected $config;

    /**
     * @param \SprykerEco\Zed\CrefoPay\CrefoPayConfig $config
     */
    public function __construct(CrefoPayConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    public function buildRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        $refundRequest = $this->createRefundRequestTransfer($crefoPayOmsCommandTransfer);
        $refundRequest
            ->setCaptureID($this->getCaptureId($crefoPayOmsCommandTransfer))
            ->setAmount(
                $this->createAmountTransfer($crefoPayOmsCommandTransfer->getRefund()->getAmount())
            );

        $requestTransfer = (new CrefoPayApiRequestTransfer())
            ->setRefundRequest($refundRequest);

        return $crefoPayOmsCommandTransfer
            ->setRequest($requestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    protected function addExpensesRequest(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        if (!$this->config->getRefundExpensesWithLastItem() || !$this->isLastRefund($crefoPayOmsCommandTransfer)) {
            return $crefoPayOmsCommandTransfer;
        }

        $refundExpenseRequestTransfer = $this->createRefundRequestTransfer($crefoPayOmsCommandTransfer);
        $refundExpenseRequestTransfer
            ->setCaptureID($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getExpensesCaptureId())
            ->setAmount(
                $this->createAmountTransfer($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getExpensesCapturedAmount())
            );

        $expensesRequestTransfer = (new CrefoPayApiRequestTransfer())
            ->setRefundRequest($refundExpenseRequestTransfer);

        return $crefoPayOmsCommandTransfer
            ->setExpensesRequest($expensesRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRefundRequestTransfer
     */
    protected function createRefundRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiRefundRequestTransfer
    {
        return (new CrefoPayApiRefundRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId())
            ->setRefundDescription($this->getRefundDescription($crefoPayOmsCommandTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @throws \SprykerEco\Zed\CrefoPay\Business\Exception\InvalidItemsToRefundAggregationException
     *
     * @return string
     */
    protected function getCaptureId(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): string
    {
        $orderItemCaptureIds = array_unique(
            array_map(
                function (PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer) {
                    return $paymentCrefoPayOrderItemTransfer->getCaptureId();
                },
                $crefoPayOmsCommandTransfer
                    ->getPaymentCrefoPayOrderItemCollection()
                    ->getCrefoPayOrderItems()
                    ->getArrayCopy()
            )
        );

        if (count($orderItemCaptureIds) !== 1) {
            throw new InvalidItemsToRefundAggregationException(static::INVALID_ITEMS_AGGREGATION_MESSAGE);
        }

        return reset($orderItemCaptureIds);
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
     * @return string|null
     */
    protected function getRefundDescription(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): ?string
    {
        return $crefoPayOmsCommandTransfer->getRefund()->getComment() ?? $this->config->getRefundDescription();
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return bool
     */
    protected function isLastRefund(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): bool
    {
        $refundedAmount = $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPay()
            ->getRefundedAmount();

        $itemsAmountToRefund = $crefoPayOmsCommandTransfer
            ->getRequest()
            ->getRefundRequest()
            ->getAmount()
            ->getAmount();

        $expensesAmountToRefund = $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPay()
            ->getExpensesCapturedAmount();

        return $refundedAmount + $itemsAmountToRefund + $expensesAmountToRefund === $crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCapturedAmount();
    }
}
