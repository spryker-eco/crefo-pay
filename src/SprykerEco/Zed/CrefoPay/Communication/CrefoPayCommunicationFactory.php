<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CancelOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CaptureOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\FinishOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\RefundOmsCommand;
use SprykerEco\Zed\CrefoPay\CrefoPayDependencyProvider;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeInterface;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeInterface;

/**
 * @method \SprykerEco\Zed\CrefoPay\Business\CrefoPayFacadeInterface getFacade()
 * @method \SprykerEco\Zed\CrefoPay\CrefoPayConfig getConfig()
 */
class CrefoPayCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandInterface
     */
    public function createCancelOmsCommand(): CrefoPayOmsCommandInterface
    {
        return new CancelOmsCommand(
            $this->getSalesFacade(),
            $this->getCalculationFacade(),
            $this->getFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandInterface
     */
    public function createCaptureOmsCommand(): CrefoPayOmsCommandInterface
    {
        return new CaptureOmsCommand(
            $this->getSalesFacade(),
            $this->getCalculationFacade(),
            $this->getFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandInterface
     */
    public function createRefundOmsCommand(): CrefoPayOmsCommandInterface
    {
        return new RefundOmsCommand(
            $this->getSalesFacade(),
            $this->getCalculationFacade(),
            $this->getFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandInterface
     */
    public function createFinishOmsCommand(): CrefoPayOmsCommandInterface
    {
        return new FinishOmsCommand(
            $this->getSalesFacade(),
            $this->getCalculationFacade(),
            $this->getFacade()
        );
    }

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
