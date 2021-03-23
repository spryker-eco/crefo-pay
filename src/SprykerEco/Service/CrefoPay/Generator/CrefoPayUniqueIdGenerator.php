<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Service\CrefoPay\CrefoPayConfig;
use SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CrefoPayUniqueIdGenerator implements CrefoPayUniqueIdGeneratorInterface
{
    protected const CREFO_PAY_ORDER_ID_INDEPENDENT_LENGTH = 30;

    /**
     * @var \SprykerEco\Service\CrefoPay\CrefoPayConfig $config
     */
    protected $config;

    /**
     * @var \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $crefoPayUniqueIdGeneratorBridge
     */
    protected $crefoPayToUtilTextServiceBridge;

    /**
     * @param \SprykerEco\Service\CrefoPay\CrefoPayConfig $config
     * @param \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $crefoPayToUtilTextServiceBridge
     */
    public function __construct(
        CrefoPayConfig $config,
        CrefoPayToUtilTextServiceInterface $crefoPayToUtilTextServiceBridge
    ) {
        $this->config = $config;
        $this->crefoPayToUtilTextServiceBridge = $crefoPayToUtilTextServiceBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        return $this->config->getUseIndependentOrderIdForTransaction() ?
            $this->generateCrefoPayOrderIdIndependent() :
            $this->generateCrefoPayOrderIdBasedOnCustomerReference($quoteTransfer);
    }

    /**
     * @deprecated Use {@link \SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGenerator::generateCrefoPayOrderIdIndependent()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function generateCrefoPayOrderIdBasedOnCustomerReference(QuoteTransfer $quoteTransfer): string
    {
        return uniqid($quoteTransfer->getCustomerReference() . '-', true);
    }

    /**
     * @return string
     */
    protected function generateCrefoPayOrderIdIndependent(): string
    {
        return $this->crefoPayToUtilTextServiceBridge->generateRandomString(self::CREFO_PAY_ORDER_ID_INDEPENDENT_LENGTH);
    }
}
