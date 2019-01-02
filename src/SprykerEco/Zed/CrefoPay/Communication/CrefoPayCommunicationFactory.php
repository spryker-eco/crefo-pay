<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\CrefoPay\CrefoPayDependencyProvider;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 */
class CrefoPayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface
     */
    public function getSalesFacade(): CrefoPayToSalesFacadeInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface
     */
    public function getCalculationFacade(): CrefoPayToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(CrefoPayDependencyProvider::FACADE_CALCULATION);
    }
}
