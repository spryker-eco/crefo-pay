<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Dependency\Facade;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;

class CrefoPayToCrefoPayApiFacadeBridge implements CrefoPayToCrefoPayApiFacadeInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPayApi\Business\CrefoPayApiFacadeInterface
     */
    protected $crefoPayApiFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPayApi\Business\CrefoPayApiFacadeInterface $crefoPayApiFacade
     */
    public function __construct($crefoPayApiFacade)
    {
        $this->crefoPayApiFacade = $crefoPayApiFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiResponseTransfer
     */
    public function performCreateTransactionApiCall(CrefoPayApiRequestTransfer $requestTransfer): CrefoPayApiResponseTransfer
    {
        return $this->crefoPayApiFacade->performCreateTransactionApiCall($requestTransfer);
    }
}
