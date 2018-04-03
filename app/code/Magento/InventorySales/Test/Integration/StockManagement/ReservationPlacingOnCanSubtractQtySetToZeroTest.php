<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventorySales\Test\Integration\StockManagement;

use Magento\InventoryReservations\Model\CleanupReservationsInterface;
use Magento\InventoryReservations\Model\GetReservationsQuantityInterface;
use Magento\InventoryReservations\Model\ReservationBuilderInterface;
use Magento\InventoryReservationsApi\Api\AppendReservationsInterface;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class ReservationPlacingOnCanSubtractQtySetToZeroTest extends TestCase
{
    /**
     * We broke transaction during indexation so we need to clean db state manually
     */
    protected function tearDown()
    {
        Bootstrap::getObjectManager()->get(CleanupReservationsInterface::class)->execute();
    }

    /**
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/products.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/sources.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stocks.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/stock_source_links.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryApi/Test/_files/source_items.php
     * @magentoDataFixture ../../../../app/code/Magento/InventoryIndexer/Test/_files/reindex_inventory.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/websites_with_stores.php
     * @magentoDataFixture ../../../../app/code/Magento/InventorySalesApi/Test/_files/stock_website_sales_channels.php
     * @magentoConfigFixture default_store cataloginventory/options/can_subtract 0
     */
    public function testPlacingReservationOnCanSubtractQtySetToZero()
    {
        $appendReservations = Bootstrap::getObjectManager()->get(AppendReservationsInterface::class);
        $reservationBuilder = Bootstrap::getObjectManager()->get(ReservationBuilderInterface::class);
        $getReservationQuantity = Bootstrap::getObjectManager()->get(GetReservationsQuantityInterface::class);

        $appendReservations->execute(
            [
                $reservationBuilder->setStockId(10)->setSku('SKU-1')->setQuantity(2)->build()
            ]
        );

        self::assertEquals(0, $getReservationQuantity->execute('SKU-1', 10));
    }
}
