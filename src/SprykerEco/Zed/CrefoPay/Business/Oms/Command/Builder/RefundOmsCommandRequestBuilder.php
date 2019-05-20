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
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

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
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer
     */
    public function buildRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayOmsCommandTransfer
    {
        $requestTransfer = (new CrefoPayApiRequestTransfer())
            ->setRefundRequest($this->createRefundRequestTransfer($crefoPayOmsCommandTransfer));

        return $crefoPayOmsCommandTransfer
            ->setRequest($requestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiRefundRequestTransfer
     */
    protected function createRefundRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiRefundRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\PaymentCrefoPayOrderItemTransfer $paymentCrefoPayOrderItemTransfer */
        $paymentCrefoPayOrderItemTransfer = $crefoPayOmsCommandTransfer
            ->getPaymentCrefoPayOrderItemCollection()
            ->getCrefoPayOrderItems()
            ->offsetGet(0);

        return (new CrefoPayApiRefundRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId())
            ->setCaptureID($paymentCrefoPayOrderItemTransfer->getCaptureId())
            ->setAmount($this->createAmountTransfer($crefoPayOmsCommandTransfer))
            ->setRefundDescription($crefoPayOmsCommandTransfer->getRefund()->getComment() ?? $this->config->getRefundDescription());
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiAmountTransfer
     */
    protected function createAmountTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiAmountTransfer
    {
        return (new CrefoPayApiAmountTransfer())
            ->setAmount($crefoPayOmsCommandTransfer->getRefund()->getAmount());
    }
}
