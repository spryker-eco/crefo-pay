<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Yves\CrefoPay;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerEco\Shared\CrefoPay\CrefoPayConstants;
use SprykerEco\Shared\CrefoPayApi\CrefoPayApiConstants;

class CrefoPayConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->get(CrefoPayApiConstants::PUBLIC_KEY);
    }

    /**
     * @return string
     */
    public function getSecureFieldsApiEndpoint(): string
    {
        return $this->get(CrefoPayConstants::SECURE_FIELDS_API_ENDPOINT);
    }

    /**
     * @return string
     */
    public function getSecureFieldsLibraryUrl(): string
    {
        return $this->get(CrefoPayConstants::SECURE_FIELDS_LIBRARY_URL);
    }

    /**
     * @return string[]
     */
    public function getSecureFieldsPlaceholders(): array
    {
        return $this->get(CrefoPayConstants::SECURE_FIELDS_PLACEHOLDERS);
    }
}
