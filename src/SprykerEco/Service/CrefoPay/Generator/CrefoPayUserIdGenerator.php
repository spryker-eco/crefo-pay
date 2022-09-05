<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay\Generator;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerEco\Service\CrefoPay\CrefoPayConfig;
use SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;

class CrefoPayUserIdGenerator implements CrefoPayUserIdGeneratorInterface
{
    /**
     * @var int
     */
    protected const GUEST_USER_ID_LENGTH = 6;

    /**
     * @var string
     */
    protected const USER_ID_B2B_SUFFIX = '-B2B';

    /**
     * @var string
     */
    protected const USER_ID_B2C_SUFFIX = '-B2C';

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
    public function generateUserId(QuoteTransfer $quoteTransfer): string
    {
        $userId = $quoteTransfer->getCustomerReference();

        if ($userId === null || $quoteTransfer->getCustomerOrFail()->getIsGuest()) {
            $userId = $this->createGuestUserId();
        }

        $userTypeSuffix = $this->getUserTypeSuffix();
        $maxUserIdLength = $this->crefoPayConfig->getCrefoPayUserIdMaxLength() - strlen($userTypeSuffix);

        if ($maxUserIdLength < strlen($userId)) {
            $userId = substr($userId, 0, $maxUserIdLength);
        }

        return sprintf('%s%s', $userId, $userTypeSuffix);
    }

    /**
     * @return string
     */
    protected function createGuestUserId(): string
    {
        return sprintf(
            'GUEST-USER-%s',
            $this->utilTextService->generateRandomString(static::GUEST_USER_ID_LENGTH),
        );
    }

    /**
     * @return string
     */
    protected function getUserTypeSuffix(): string
    {
        return $this->crefoPayConfig->getIsBusinessToBusiness() ? static::USER_ID_B2B_SUFFIX : static::USER_ID_B2C_SUFFIX;
    }
}
