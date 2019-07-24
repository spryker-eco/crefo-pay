<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Zed\CrefoPay;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCalculationFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToCrefoPayApiFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToLocaleFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToOmsFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToRefundFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Facade\CrefoPayToSalesFacadeBridge;
use SprykerEco\Zed\CrefoPay\Dependency\Service\CrefoPayToUtilTextServiceBridge;

class CrefoPayDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CREFO_APY_API = 'FACADE_CREFO_APY_API';
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_CALCULATION = 'FACADE_CALCULATION';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_OMS = 'FACADE_OMS';
    public const FACADE_REFUND = 'FACADE_REFUND';

    public const SERVICE_CREFO_PAY = 'SERVICE_CREFO_PAY';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCrefoPayApiFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addCrefoPayService($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addRefundFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addCalculationFacade($container);
        $container = $this->addRefundFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCrefoPayApiFacade(Container $container): Container
    {
        $container->set(static::FACADE_CREFO_APY_API, function (Container $container) {
            return new CrefoPayToCrefoPayApiFacadeBridge($container->getLocator()->crefoPayApi()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new CrefoPayToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCalculationFacade(Container $container): Container
    {
        $container->set(static::FACADE_CALCULATION, function (Container $container) {
            return new CrefoPayToCalculationFacadeBridge($container->getLocator()->calculation()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CrefoPayToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new CrefoPayToOmsFacadeBridge($container->getLocator()->oms()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRefundFacade(Container $container): Container
    {
        $container->set(static::FACADE_REFUND, function (Container $container) {
            return new CrefoPayToRefundFacadeBridge($container->getLocator()->refund()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCrefoPayService(Container $container): Container
    {
        $container->set(static::SERVICE_CREFO_PAY, function (Container $container) {
            return $container->getLocator()->crefoPay()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new CrefoPayToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }
}
