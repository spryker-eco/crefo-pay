<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Processor\Notification;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use SprykerEco\Client\CrefoPay\CrefoPayClientInterface;
use SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToCrefoPayApiServiceInterface;
use SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface;
use Symfony\Component\HttpFoundation\Request;

class CrefoPayNotificationProcessor implements CrefoPayNotificationProcessorInterface
{
    protected const API_FIELD_MAC = 'mac';

    /**
     * @var \SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface
     */
    protected $mapper;

    /**
     * @var \SprykerEco\Client\CrefoPay\CrefoPayClientInterface
     */
    protected $crefoPayClient;

    /**
     * @var \SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToCrefoPayApiServiceInterface
     */
    protected $crefoPayApiService;

    /**
     * @param \SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper\CrefoPayNotificationProcessorMapperInterface $mapper
     * @param \SprykerEco\Client\CrefoPay\CrefoPayClientInterface $crefoPayClient
     * @param \SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToCrefoPayApiServiceInterface $crefoPayApiService
     */
    public function __construct(
        CrefoPayNotificationProcessorMapperInterface $mapper,
        CrefoPayClientInterface $crefoPayClient,
        CrefoPayToCrefoPayApiServiceInterface $crefoPayApiService
    ) {
        $this->mapper = $mapper;
        $this->crefoPayClient = $crefoPayClient;
        $this->crefoPayApiService = $crefoPayApiService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function processNotification(Request $request): void
    {
        if (!$this->validateMac($request)) {
            return;
        }

        $notificationTransfer = $this->mapper
            ->mapRequestToNotificationTransfer(
                $request,
                new CrefoPayNotificationTransfer()
            );

        $this->crefoPayClient->processNotification($notificationTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function validateMac(Request $request): bool
    {
        $requestParams = $request->request->all();
        $macString = $requestParams[static::API_FIELD_MAC];
        unset($requestParams[static::API_FIELD_MAC]);

        return $this->crefoPayApiService->validateMac($requestParams, $macString);
    }
}
