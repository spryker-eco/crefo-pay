<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Processor\Notification;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use SprykerEco\Client\CrefoPay\CrefoPayClientInterface;
use SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface;
use Symfony\Component\HttpFoundation\Request;

class CrefoPayNotificationProcessor implements CrefoPayNotificationProcessorInterface
{
    /**
     * @var \SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface
     */
    protected $mapper;

    /**
     * @var \SprykerEco\Client\CrefoPay\CrefoPayClientInterface
     */
    protected $crefoPayClient;

    /**
     * @param \SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface $mapper
     * @param \SprykerEco\Client\CrefoPay\CrefoPayClientInterface $crefoPayClient
     */
    public function __construct(
        CrefoPayNotificationProcessorMapperInterface $mapper,
        CrefoPayClientInterface $crefoPayClient
    ) {
        $this->mapper = $mapper;
        $this->crefoPayClient = $crefoPayClient;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function processNotification(Request $request): void
    {
        $notificationTransfer = $this->mapper
            ->mapRequestToNotificationTransfer(
                $request,
                new CrefoPayNotificationTransfer()
            );

        $this->crefoPayClient->processNotification($notificationTransfer);
    }
}
