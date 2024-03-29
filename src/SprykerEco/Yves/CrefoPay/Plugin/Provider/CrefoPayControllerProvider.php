<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use {@link \SprykerEco\Yves\CrefoPay\Plugin\Router\CrefoPayRouteProviderPlugin} instead.
 */
class CrefoPayControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    protected const BUNDLE_NAME = 'CrefoPay';

    /**
     * @var string
     */
    protected const CALLBACK_CONTROLLER_NAME = 'Callback';

    /**
     * @var string
     */
    protected const NOTIFICATION_CONTROLLER_NAME = 'Notification';

    /**
     * @var string
     */
    protected const CREFO_PAY_NOTIFICATION = 'crefo-pay-notification';

    /**
     * @var string
     */
    protected const CREFO_PAY_CONFIRMATION = 'crefo-pay-confirmation';

    /**
     * @var string
     */
    protected const CREFO_PAY_SUCCESS = 'crefo-pay-success';

    /**
     * @var string
     */
    protected const CREFO_PAY_FAILURE = 'crefo-pay-failure';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->createController(
            '/crefo-pay/notification',
            static::CREFO_PAY_NOTIFICATION,
            static::BUNDLE_NAME,
            static::NOTIFICATION_CONTROLLER_NAME,
            'index',
        );

        $this->createController(
            '/crefo-pay/callback/confirmation',
            static::CREFO_PAY_CONFIRMATION,
            static::BUNDLE_NAME,
            static::CALLBACK_CONTROLLER_NAME,
            'confirmation',
        );

        $this->createController(
            '/crefo-pay/callback/success',
            static::CREFO_PAY_SUCCESS,
            static::BUNDLE_NAME,
            static::CALLBACK_CONTROLLER_NAME,
            'success',
        );

        $this->createController(
            '/crefo-pay/callback/failure',
            static::CREFO_PAY_FAILURE,
            static::BUNDLE_NAME,
            static::CALLBACK_CONTROLLER_NAME,
            'failure',
        );
    }
}
