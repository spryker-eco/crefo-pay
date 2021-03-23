<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Spryker\Service\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;

/**
 * @method \SprykerEco\Shared\CrefoPay\CrefoPayConfig getSharedConfig()
 */
class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return bool
     */
    public function getUseIndependentOrderIdForTransaction(): bool
    {
        return $this->get(CrefoPayConstants::USE_INDEPENDENT_ORDER_ID_FOR_TRANSACTION, false);
    }
}
