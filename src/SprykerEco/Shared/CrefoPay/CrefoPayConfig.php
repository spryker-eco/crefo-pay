<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Shared\CrefoPay;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class CrefoPayConfig extends AbstractBundleConfig
{
    public const INTEGRATION_TYPE = 'API'; //Possible values: API, SecureFields, HostedPageBefore, HostedPageAfter.
    public const CONTEXT = 'ONLINE'; //Possible values: ONLINE, OFFLINE.
    public const USER_TYPE = 'PRIVATE'; //Possible values: PRIVATE, BUSINESS.
    public const USER_RISK_CLASS = 0; //Possible values: 0 -> trusted user, 1 -> default risk user, 2 -> high risk user.
}
