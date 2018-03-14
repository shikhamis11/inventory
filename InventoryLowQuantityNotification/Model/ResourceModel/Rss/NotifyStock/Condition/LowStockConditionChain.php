<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryLowQuantityNotification\Model\ResourceModel\Rss\NotifyStock\Condition;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;

class LowStockConditionChain
{
    /**
     * @var []
     */
    private $conditions = [];

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     * @param array $conditions
     *
     * @throws LocalizedException
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        array $conditions = []
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->conditions = $conditions;
    }

    /**
     * @inheritdoc
     */
    public function execute(): string
    {
        if (empty($this->conditions)) {
            return '1';
        }

        $conditionStrings = [];
        foreach ($this->conditions as $condition) {
            $conditionString = $condition->execute();
            if ('' !== trim($conditionString)) {
                $conditionStrings[] = $conditionString;
            }
        }

        $lowStockConditionString = '(' . implode($conditionStrings, ') AND (') . ')';

        return $lowStockConditionString;
    }
}
