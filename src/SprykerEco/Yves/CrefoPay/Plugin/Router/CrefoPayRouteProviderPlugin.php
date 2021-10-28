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
    /**
     * @var string
     */
    protected const BUNDLE_NAME = 'CrefoPay';

    /**
     * @var string
     */
    protected const CONTROLLER_NAME_CALLBACK = 'Callback';

    /**
     * @var string
     */
    protected const CONTROLLER_NAME_NOTIFICATION = 'Notification';

    /**
     * @var string
     */
    protected const ROUTE_NAME_CREFO_PAY_NOTIFICATION = 'crefo-pay-notification';

    /**
     * @var string
     */
    protected const ROUTE_NAME_CREFO_PAY_CONFIRMATION = 'crefo-pay-confirmation';

    /**
     * @var string
     */
    protected const ROUTE_NAME_CREFO_PAY_SUCCESS = 'crefo-pay-success';

    /**
     * @var string
     */
    protected const ROUTE_NAME_CREFO_PAY_FAILURE = 'crefo-pay-failure';

    /**
     * {@inheritDoc}
     *
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
        $route = $this->buildRoute('/crefo-pay/notification', static::BUNDLE_NAME, static::CONTROLLER_NAME_NOTIFICATION, 'index');
        $routeCollection->add(static::ROUTE_NAME_CREFO_PAY_NOTIFICATION, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPayConfirmationRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/crefo-pay/callback/confirmation', static::BUNDLE_NAME, static::CONTROLLER_NAME_CALLBACK, 'confirmation');
        $routeCollection->add(static::ROUTE_NAME_CREFO_PAY_CONFIRMATION, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPaySuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/crefo-pay/callback/success', static::BUNDLE_NAME, static::CONTROLLER_NAME_CALLBACK, 'success');
        $routeCollection->add(static::ROUTE_NAME_CREFO_PAY_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCrefoPayFailureRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/crefo-pay/callback/failure', static::BUNDLE_NAME, static::CONTROLLER_NAME_CALLBACK, 'failure');
        $routeCollection->add(static::ROUTE_NAME_CREFO_PAY_FAILURE, $route);

        return $routeCollection;
    }
}
