<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Spryker\Service\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;

class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const CREFO_PAY_ORDER_ID_LENGTH = 30;

    /**
     * @var int
     */
    protected const CREFO_PAY_USER_ID_MAX_LENGTH = 50;

    /**
     * @var int
     */
    protected const CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH = 20;

    /**
     * @api
     *
     * @return int
     */
    public function getCrefoPayOrderIdLength(): int
    {
        return static::CREFO_PAY_ORDER_ID_LENGTH;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCrefoPayUserIdMaxLength(): int
    {
        return static::CREFO_PAY_USER_ID_MAX_LENGTH;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getCrefoPayBasketItemIdMaxLength(): int
    {
        return static::CREFO_PAY_BASKET_ITEM_ID_MAX_LENGTH;
    }

    /**
     * Specification:
     * - Represents the integration model.
     * - Returns `true` in case of B2B.
     * - Returns `false` in case of B2C.
     *
     * @api
     *
     * @return bool
     */
    public function isBusinessToBusiness(): bool
    {
        return $this->get(CrefoPayConstants::IS_BUSINESS_TO_BUSINESS, false);
    }
}
