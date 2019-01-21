<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Processor;

use Symfony\Component\HttpFoundation\Request;

interface CrefoPayNotificationProcessorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function processNotification(Request $request): void;
}
