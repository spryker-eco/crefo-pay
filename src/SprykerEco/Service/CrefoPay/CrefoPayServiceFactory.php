<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Spryker\Service\Kernel\AbstractServiceFactory;
use SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface;
use SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGenerator;
use SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGeneratorInterface;

class CrefoPayServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGeneratorInterface
     */
    public function createUniqueIdGenerator(): CrefoPayUniqueIdGeneratorInterface
    {
        return new CrefoPayUniqueIdGenerator($this->getUtilTextService());
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    protected function getUtilTextService(): CrefoPayToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
