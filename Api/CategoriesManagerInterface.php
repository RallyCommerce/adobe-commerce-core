<?php

namespace Rally\Checkout\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Exception;
use Rally\Checkout\Api\Data\CategoriesDataInterface;

/**
 * Interface CategoriesManagerInterface
 * @api
 */
interface CategoriesManagerInterface
{
    /**
     * GET product categories
     *
     * @param string $orgId
     * @return CategoriesDataInterface[]
     * @throws Exception
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function get(string $orgId): array;
}
