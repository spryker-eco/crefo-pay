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
     * @var \SprykerEco\Service\CrefoPay\CrefoPayConfig $crefoPayConfig
     */
    protected $crefoPayConfig;

    /**
     * @var \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $utilTextService
     */
    protected $utilTextService;

    /**
     * @param \SprykerEco\Service\CrefoPay\CrefoPayConfig $crefoPayConfig
     * @param \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        CrefoPayConfig $crefoPayConfig,
        CrefoPayToUtilTextServiceInterface $utilTextService
    ) {
        $this->crefoPayConfig = $crefoPayConfig;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string
    {
        return $this->crefoPayConfig->getUseIndependentOrderIdForTransaction() ?
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
        return $this->utilTextService->generateRandomString(static::CREFO_PAY_ORDER_ID_INDEPENDENT_LENGTH);
    }
}
