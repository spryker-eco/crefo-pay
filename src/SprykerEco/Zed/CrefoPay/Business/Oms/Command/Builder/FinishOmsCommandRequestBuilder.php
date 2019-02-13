<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\Builder;

use Generated\Shared\Transfer\CrefoPayApiFinishRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayOmsCommandTransfer;
use Generated\Shared\Transfer\PaymentCrefoPayTransfer;
use SprykerEco\Zed\CrefoPay\CrefoPayConfig;

class FinishOmsCommandRequestBuilder implements CrefoPayOmsCommandRequestBuilderInterface
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
            ->setFinishRequest($this->createFinishRequestTransfer($crefoPayOmsCommandTransfer));

        return $crefoPayOmsCommandTransfer
            ->setRequest($requestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiFinishRequestTransfer
     */
    protected function createFinishRequestTransfer(CrefoPayOmsCommandTransfer $crefoPayOmsCommandTransfer): CrefoPayApiFinishRequestTransfer
    {
        return (new CrefoPayApiFinishRequestTransfer())
            ->setMerchantID($this->config->getMerchantId())
            ->setStoreID($this->config->getStoreId())
            ->setOrderID($crefoPayOmsCommandTransfer->getPaymentCrefoPay()->getCrefoPayOrderId());
    }
}
