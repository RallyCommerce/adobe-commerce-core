<?php

namespace Rally\Checkout\Api;

use Magento\Framework\Webapi\Exception;
use Rally\Checkout\Api\Data\ProductsDataInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;

/**
 * Interface ProductsManagerInterface
 * @api
 */
interface ProductsManagerInterface
{
    /**
     * GET products data
     *
     * @param string $orgId
     * @return ProductsDataInterface[]
     * @throws Exception
     * @throws NoSuchEntityException
     */
    public function get(string $orgId): array;

    /**
     * GET product data
     *
     * @param string $orgId
     * @param Product|ProductInterface $product
     * @return ProductsDataInterface
     * @throws NoSuchEntityException
     */
    public function getProductData(string $orgId, Product|ProductInterface $product): ProductsDataInterface;
}
