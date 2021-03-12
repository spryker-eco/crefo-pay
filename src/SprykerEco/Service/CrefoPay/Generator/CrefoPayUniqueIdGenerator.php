<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CrefoPayUniqueIdGenerator implements CrefoPayUniqueIdGeneratorInterface
{
    /**
     * @var SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $crefoPayUniqueIdGeneratorBridge
     */
    protected $crefoPayToUtilTextServiceBridge;

    /**
     * CrefoPayUniqueIdGenerator constructor.
     *
     * @param SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $crefoPayUniqueIdGeneratorBridge
     */
    public function __construct(CrefoPayToUtilTextServiceInterface $crefoPayToUtilTextServiceBridge)
    {
        $this->crefoPayToUtilTextServiceBridge = $crefoPayToUtilTextServiceBridge;
    }

    /**
     * @deprecated Use {@link generateCrefoPayOrderIdIndependent()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        return uniqid($quoteTransfer->getCustomerReference() . '-', true);
    }

    /**
     * @return string
     */
    public function generateCrefoPayOrderIdIndependent(): string
    {
        return $this->crefoPayToUtilTextServiceBridge->generateRandomString(30);
    }
}
