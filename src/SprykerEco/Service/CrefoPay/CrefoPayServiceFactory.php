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
use SprykerEco\Service\CrefoPay\Generator\CrefoPayUserIdGenerator;
use SprykerEco\Service\CrefoPay\Generator\CrefoPayUserIdGeneratorInterface;

/**
 * @method \SprykerEco\Service\CrefoPay\CrefoPayConfig getConfig()
 */
class CrefoPayServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \SprykerEco\Service\CrefoPay\Generator\CrefoPayUniqueIdGeneratorInterface
     */
    public function createUniqueIdGenerator(): CrefoPayUniqueIdGeneratorInterface
    {
        return new CrefoPayUniqueIdGenerator(
            $this->getConfig(),
            $this->getUtilTextService(),
        );
    }

    /**
     * @return \SprykerEco\Service\CrefoPay\Generator\CrefoPayUserIdGeneratorInterface
     */
    public function createCrefoPayUserIdGenerator(): CrefoPayUserIdGeneratorInterface
    {
        return new CrefoPayUserIdGenerator(
            $this->getConfig(),
            $this->getUtilTextService(),
        );
    }

    /**
     * @return \SprykerEco\Service\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceInterface
     */
    public function getUtilTextService(): CrefoPayToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
