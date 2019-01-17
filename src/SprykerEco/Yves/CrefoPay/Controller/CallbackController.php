<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerEco\Yves\CrefoPay\CrefoPayFactory getFactory()
 */
class CallbackController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmationAction(Request $request): RedirectResponse
    {
        return $this->handleRedirect();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function successAction(Request $request): RedirectResponse
    {
        return $this->handleRedirect();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function failureAction(Request $request): RedirectResponse
    {
        return $this->handleRedirect();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function handleRedirect(): RedirectResponse
    {
        return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_ERROR);
    }
}
