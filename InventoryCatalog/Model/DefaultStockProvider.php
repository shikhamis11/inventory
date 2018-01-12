<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryCatalog\Model;

use Magento\InventoryCatalog\Api\DefaultStockProviderInterface;

/**
 * Service returns Default Stock Id
 */
class DefaultStockProvider implements DefaultStockProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getId(): int
    {
        return \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID;
    }
}