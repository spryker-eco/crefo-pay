<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiAmountTransfer;
use Generated\Shared\Transfer\CrefoPayApiCaptureRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRefundRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer;
use Generated\Shared\Transfer\CrefoPayToSalesOrderItemTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class RefundOmsCommandRequestBuilder implements CrefoPayOmsCommandRequestBuilderInterface
{
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRequestTransfer
     */
    public function buildRequestTransfer(
        OrderTransfer $orderTransfer,
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
    ): CrefoPayApiRequestTransfer {
        $refundRequestTransfer = $this->createRefundRequestTransfer(
            $crefoPayOmsCommandTransfer->getPaymentCrefoPay()
        );
        $refundRequestTransfer->setAmount(
            $this->createAmountTransfer(
                $orderTransfer,
                $crefoPayOmsCommandTransfer
            )
        );

        return (new CrefoPayApiRequestTransfer())
            ->setRefundRequest($refundRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentCrefoPayTransfer $paymentCrefoPayTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiCaptureRequestTransfer
     */
    protected function createRefundRequestTransfer(PaymentCrefoPayTransfer $paymentCrefoPayTransfer): CrefoPayApiRefundRequestTransfer
    {
        return (new CrefoPayApiRefundRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($paymentCrefoPayTransfer->getCrefoPayOrderId())
            ->setCaptureID('14acf3b5833637911638bab0cb2917')
            ->setRefundDescription('RefundDescription');
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAmountTransfer
     */
    protected function createAmountTransfer(
        OrderTransfer $orderTransfer,
        CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
    ): CrefoPayApiAmountTransfer {
        $amountTransfer = new CrefoPayApiAmountTransfer();
        $crefoPayToSalesOrderItemsCollection = $crefoPayOmsCommandTransfer->getCrefoPayToSalesOrderItemsCollection();

        $captureAmount = $this->calculateOrderItemsAmount($crefoPayToSalesOrderItemsCollection);

        return $amountTransfer
            ->setAmount(23026);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection
     *
     * @return int
     */
    protected function calculateOrderItemsAmount(CrefoPayToSalesOrderItemsCollectionTransfer $crefoPayToSalesOrderItemsCollection): int
    {
        return array_sum(
            array_map(
                function (CrefoPayToSalesOrderItemTransfer $crefoPayToSalesOrderItem) {
                    return $crefoPayToSalesOrderItem->getRefundableAmount();
                },
                $crefoPayToSalesOrderItemsCollection->getCrefoPayToSalesOrderItems()->getArrayCopy()
            )
        );
    }
}
