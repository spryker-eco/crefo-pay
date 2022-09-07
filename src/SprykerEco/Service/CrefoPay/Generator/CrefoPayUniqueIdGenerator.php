<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Service\CrefoPay\CrefoPayConfig;
use SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CrefoPayUniqueIdGenerator implements CrefoPayUniqueIdGeneratorInterface
{
    /**
     * @var int
     */
    protected const RANDOM_STRING_MIN_LENGTH = 3;

    /**
     * @var \SprykerEco\Service\CrefoPay\CrefoPayConfig
     */
    protected CrefoPayConfig $crefoPayConfig;

    /**
     * @var \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    protected CrefoPayToUtilTextServiceInterface $utilTextService;

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
        return $this->generateUniqueId(
            $this->crefoPayConfig->getCrefoPayOrderIdLength(),
            $quoteTransfer->getCustomerReference(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function generateBasketItemId(ItemTransfer $itemTransfer): string
    {
        $basketItemIdMaxLength = $this->crefoPayConfig->getCrefoPayBasketItemIdMaxLength();
        $normalizedSku = $this->normalizeBase(
            $basketItemIdMaxLength,
            $itemTransfer->getSku(),
        );

        if ($normalizedSku === $itemTransfer->getSku()) {
            return $itemTransfer->getSku();
        }

        return $this->generateUniqueId(
            $basketItemIdMaxLength,
            $itemTransfer->getSku(),
        );
    }

    /**
     * @param int $maxLength
     * @param string|null $base
     *
     * @return string
     */
    protected function generateUniqueId(int $maxLength, ?string $base = null): string
    {
        $baseMaxLength = $maxLength - static::RANDOM_STRING_MIN_LENGTH - 1;
        $base = $this->normalizeBase($baseMaxLength, $base);
        $randomStringLength = $maxLength - strlen($base) - 1;

        return sprintf(
            '%s-%s',
            $this->utilTextService->generateRandomString($randomStringLength),
            $base,
        );
    }

    /**
     * @param int $maxLength
     * @param string|null $base
     *
     * @return string
     */
    protected function normalizeBase(int $maxLength, ?string $base = null): string
    {
        if ($base === null) {
            return '';
        }

        if ($maxLength >= strlen($base)) {
            return $base;
        }

        return substr($base, 0, $maxLength);
    }
}
