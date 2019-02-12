<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CancelOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CaptureOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandByOrderInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\FinishOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandByItemInterface;
use SprykerEco\Zed\CrefoPay\Communication\Oms\Command\RefundOmsCommand;
use SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapper;
use SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface;
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
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandByOrderInterface
     */
    public function createCancelOmsCommand(): CrefoPayOmsCommandByOrderInterface
    {
        return new CancelOmsCommand(
            $this->createCrefoPayOmsMapper(),
            $this->getFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandByOrderInterface
     */
    public function createCaptureOmsCommand(): CrefoPayOmsCommandByOrderInterface
    {
        return new CaptureOmsCommand(
            $this->createCrefoPayOmsMapper(),
            $this->getFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandByItemInterface
     */
    public function createRefundOmsCommand(): CrefoPayOmsCommandByItemInterface
    {
        return new RefundOmsCommand(
            $this->createCrefoPayOmsMapper(),
            $this->getFacade()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\Command\CrefoPayOmsCommandByOrderInterface
     */
    public function createFinishOmsCommand(): CrefoPayOmsCommandByOrderInterface
    {
        return new FinishOmsCommand(
            $this->createCrefoPayOmsMapper(),
            $this->getFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\CrefoPay\Communication\Oms\CrefoPayOmsMapperInterface
     */
    public function createCrefoPayOmsMapper(): CrefoPayOmsMapperInterface
    {
        return new CrefoPayOmsMapper(
            $this->getSalesFacade(),
            $this->getCalculationFacade()
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
