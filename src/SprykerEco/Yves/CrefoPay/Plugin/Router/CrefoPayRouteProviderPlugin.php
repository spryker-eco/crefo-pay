<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CrefoPayRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const BUNDLE_NAME = 'CrefoPay';
    protected const CALLBACK_CONTROLLER_NAME = 'Callback';
    protected const NOTIFICATION_CONTROLLER_NAME = 'Notification';
    protected const CREFO_PAY_NOTIFICATION = 'crefo-pay-notification';
    protected const CREFO_PAY_CONFIRMATION = 'crefo-pay-confirmation';
    protected const CREFO_PAY_SUCCESS = 'crefo-pay-success';
    protected const CREFO_PAY_FAILURE = 'crefo-pay-failure';

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCrefoPayNotificationRoute($routeCollection);
        $routeCollection = $this->addCrefoPayConfirmationRoute($routeCollection);
        $routeCollection = $this->addCrefoPaySuccessRoute($routeCollection);
        $routeCollection = $this->addCrefoPayFailureRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPayNotificationRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::CREFO_PAY_NOTIFICATION,
            $this->buildPostRoute('/crefo-pay/notification', static::BUNDLE_NAME, static::NOTIFICATION_CONTROLLER_NAME, 'index')
        );

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPayConfirmationRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::CREFO_PAY_CONFIRMATION,
            $this->buildPostRoute('/crefo-pay/callback/confirmation', static::BUNDLE_NAME, static::CALLBACK_CONTROLLER_NAME, 'confirmation')
        );

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPaySuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::CREFO_PAY_SUCCESS,
            $this->buildPostRoute('/crefo-pay/callback/success', static::BUNDLE_NAME, static::CALLBACK_CONTROLLER_NAME, 'success')
        );

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPayFailureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection->add(
            static::CREFO_PAY_FAILURE,
            $this->buildPostRoute('/crefo-pay/callback/failure', static::BUNDLE_NAME, static::CALLBACK_CONTROLLER_NAME, 'failure')
        );

        return $routeCollection;
    }
}
