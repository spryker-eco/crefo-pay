<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerEco\Yves\CrefoPay\CrefoPayFactory getFactory()
 */
class NotificationController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        return $this->createAcceptedResponse();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function createAcceptedResponse(): Response
    {
        return new Response('', Response::HTTP_OK);
    }
}
