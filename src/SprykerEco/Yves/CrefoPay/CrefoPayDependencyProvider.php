<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToCrefoPayApiServiceBridge;

class CrefoPayDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_CREFO_PAY_API = 'SERVICE_CREFO_PAY_API';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCrefoPayApiService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCrefoPayApiService(Container $container): Container
    {
        $container->set(static::SERVICE_CREFO_PAY_API, function (Container $container) {
            return new CrefoPayToCrefoPayApiServiceBridge($container->getLocator()->crefoPayApi()->service());
        });

        return $container;
    }
}
