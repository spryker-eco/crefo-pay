<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Processor\Notification\Mapper;

use Generated\Shared\Transfer\CrefoPayNotificationTransfer;
use Symfony\Component\HttpFoundation\Request;

class CrefoPayNotificationProcessorMapper implements CrefoPayNotificationProcessorMapperInterface
{
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
        return (new CrefoPayNotificationTransfer())
            ->fromArray($request->request->all(), true);
    }
}
