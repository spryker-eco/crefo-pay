<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Dependency\Facade;

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
}
