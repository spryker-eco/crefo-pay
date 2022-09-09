<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerEco\Service\CrefoPay;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CrefoPayServiceInterface
{
    /**
     * Specification:
     * - Generates unique identifier for order id in CrefoPay system.
     * - If `QuoteTransfer.customerReference` is set, uses it as a base.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayOrderId(QuoteTransfer $quoteTransfer): string;

    /**
     * Specification:
     * - If `ItemTransfer.sku` is set and length is lower than max length, returns `ItemTransfer.sku`.
     * - Otherwise, generates unique basket item ID based on `ItemTransfer.sku`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function generateCrefoPayBasketItemId(ItemTransfer $itemTransfer): string;

    /**
     * Specification:
     * - Requires `QuoteTransfer.customer` transfer property to be set.
     * - Generates unique user ID for CrefoPay system.
     * - If `QuoteTransfer.customerReference` is provided and the customer is not a guest, uses `QuoteTransfer.customerReference` for ID generation.
     * - Otherwise, generates ID using guest user pattern.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function generateCrefoPayUserId(QuoteTransfer $quoteTransfer): string;
}
