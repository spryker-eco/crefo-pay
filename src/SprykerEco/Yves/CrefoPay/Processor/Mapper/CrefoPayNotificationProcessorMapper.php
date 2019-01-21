<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Processor\Mapper;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToUtilEncodingServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class CrefoPayNotificationProcessorMapper implements CrefoPayNotificationProcessorMapperInterface
{
    /**
     * @var \SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerEco\Yves\CrefoPay\Dependency\Service\CrefoPayToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(CrefoPayToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\CrefoPayNotificationTransfer $notificationTransfer
     *
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    public function mapRequestToNotificationTransfer(
        Request $request,
        CrefoPayNotificationTransfer $notificationTransfer
    ): CrefoPayNotificationTransfer {
        $response = $this->utilEncodingService->decodeJson((string)$request->getContent(), true);

        return $this->createNotificationTransfer($response);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CrefoPayNotificationTransfer
     */
    protected function createNotificationTransfer(array $data): CrefoPayNotificationTransfer
    {
        return (new CrefoPayNotificationTransfer())
            ->fromArray($data, true);
    }
}
