<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiCancelRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class CancelOmsCommandRequestBuilder implements CrefoPayOmsCommandRequestBuilderInterface
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
            ->setCancelRequest($this->createCancelRequestTransfer($crefoPayOmsCommandTransfer));

        return $crefoPayOmsCommandTransfer
            ->setRequest($requestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiCancelRequestTransfer
     */
    protected function createCancelRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiCancelRequestTransfer
    {
        return (new CrefoPayApiCancelRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId());
    }
}
