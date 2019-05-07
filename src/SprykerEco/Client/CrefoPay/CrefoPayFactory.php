<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Client\CrefoPay;

use Spryker\Client\Kernel\AbstractFactory;
use SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface;
use SprykerEco\Client\CrefoPay\Zed\CrefoPayStub;
use SprykerEco\Client\CrefoPay\Zed\CrefoPayStubInterface;

class CrefoPayFactory extends AbstractFactory
{
    /**
     * @return \SprykerEco\Client\CrefoPay\Zed\CrefoPayStubInterface
     */
    public function createZedCrefoPayStub(): CrefoPayStubInterface
    {
        return new CrefoPayStub($this->getZedRequestClient());
    }

    /**
     * @return \SprykerEco\Client\CrefoPay\Dependency\Client\CrefoPayToZedRequestClientInterface
     */
    public function getZedRequestClient(): CrefoPayToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
