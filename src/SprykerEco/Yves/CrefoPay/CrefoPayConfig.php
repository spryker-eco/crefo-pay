<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPayApi\CrefoPayApiConfig;

class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getApiFieldMac(): string
    {
        return CrefoPayApiConfig::API_FIELD_MAC;
    }
}
