<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Spryker\Service\Kernel\AbstractBundleConfig;

class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @var int
     */
    protected const CREFO_PAY_ORDER_ID_LENGTH = 30;

    /**
     * @api
     *
     * @return int
     */
    public function getCrefoPayOrderIdLength(): int
    {
        return static::CREFO_PAY_ORDER_ID_LENGTH;
    }
}
