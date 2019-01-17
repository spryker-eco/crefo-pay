<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class CrefoPayControllerProvider extends AbstractYvesControllerProvider
{
    protected const BUNDLE_NAME = 'CrefoPay';
    protected const CALLBACK_CONTROLLER_NAME = 'Callback';
    protected const NOTIFICATION_CONTROLLER_NAME = 'Notification';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->createController(
            '/crefo-pay/notification',
            'crefo-pay-notification',
            static::BUNDLE_NAME,
            static::NOTIFICATION_CONTROLLER_NAME,
            'index'
        );

        $this->createController(
            '/crefo-pay/callback/confirmation',
            'crefo-pay-confirmation',
            static::BUNDLE_NAME,
            static::CALLBACK_CONTROLLER_NAME,
            'confirmation'
        );

        $this->createController(
            '/crefo-pay/callback/success',
            'crefo-pay-success',
            static::BUNDLE_NAME,
            static::CALLBACK_CONTROLLER_NAME,
            'success'
        );

        $this->createController(
            '/crefo-pay/callback/failure',
            'crefo-pay-failure',
            static::BUNDLE_NAME,
            static::CALLBACK_CONTROLLER_NAME,
            'failure'
        );
    }
}
