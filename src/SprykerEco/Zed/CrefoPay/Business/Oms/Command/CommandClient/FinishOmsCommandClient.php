<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Business\Oms\Command\CommandClient;

use Generated\Shared\Transfer\CrefoPayApiRequestTransfer;
use Generated\Shared\Transfer\CrefoPayApiResponseTransfer;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface;

class FinishOmsCommandClient implements CrefoPayOmsCommandClientInterface
{
    /**
     * @var \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface
     */
    protected $crefoPayApiFacade;

    /**
     * @param \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
     */
    public function __construct(
        CrefoPayToCrefoPayApiFacadeInterface $crefoPayApiFacade
    ) {
        $this->crefoPayApiFacade = $crefoPayApiFacade;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\CrefoPayApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayApiResponseTransfer
     */
    public function performApiCall(CrefoPayApiRequestTransfer $requestTransfer): CrefoPayApiResponseTransfer
    {
        return $this->crefoPayApiFacade->performFinishApiCall($requestTransfer);
    }
}
