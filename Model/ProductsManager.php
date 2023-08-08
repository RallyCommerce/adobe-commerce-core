<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Framework\App\Area;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\App\Emulation;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Rally\Checkout\Api\Data\ProductVariantsDataInterface;
use Rally\Checkout\Api\ProductsManagerInterface;
use Rally\Checkout\Api\Data\ProductsDataInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type as ProductType;
use Rally\Checkout\Api\Data\ProductsDataInterfaceFactory;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Rally\Checkout\Api\Data\ProductImagesDataInterfaceFactory;
use Rally\Checkout\Api\Data\VariantValuesDataInterfaceFactory;
use Rally\Checkout\Api\Data\ProductOptionsDataInterfaceFactory;
use Rally\Checkout\Api\Data\VariantsPricesDataInterfaceFactory;
use Rally\Checkout\Api\Data\ProductVariantsDataInterfaceFactory;
use Rally\Checkout\Api\Data\ProductOptionValuesDataInterfaceFactory;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Downloadable\Model\Product\Type as DownloadableType;
use Magento\Catalog\Model\Product;

/**
 * Checkout ProductsManager model
 * @api
 */
class ProductsManager implements ProductsManagerInterface
{
    public function __construct(
        public StoreManagerInterface $storeManager,
        public Emulation $appEmulation,
        public RequestValidatorInterface $requestValidator,
        public SearchCriteriaBuilder $searchCriteriaBuilder,
        public ProductRepositoryInterface $productRepository,
        public ProductsDataInterfaceFactory $productDataFactory,
        public ProductImagesDataInterfaceFactory $productImgDataFactory,
        public Request $request,
        public Image $imageHelper,
        public StockItemRepository $stockItemRepository,
        public ProductOptionsDataInterfaceFactory $optionsFactory,
        public ProductOptionValuesDataInterfaceFactory $optionValuesFactory,
        public ProductVariantsDataInterfaceFactory $productVariantsFactory,
        public VariantsPricesDataInterfaceFactory $variantsPricesFactory,
        public VariantValuesDataInterfaceFactory $variantValueFactory,
        public GetSalableQuantityDataBySku $getSalableQtyDataBySku
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId): array
    {
        $productType = [ProductType::TYPE_SIMPLE, ConfigurableType::TYPE_CODE];
        $this->requestValidator->validate();
        $body = $this->request->getBodyParams();
        $store = $this->storeManager->getStore();
        $response = [];

        if (!empty($body['product_id'])) {
            try {
                $product = $this->productRepository->getById($body['product_id']);
            } catch (\Exception $e) {
                return $response;
            }
            if ($product->getStatus() == Status::STATUS_ENABLED &&
                $product->getVisibility() != Visibility::VISIBILITY_NOT_VISIBLE &&
                in_array($product->getTypeId(), $productType)
            ) {
                $response[] = $this->getProductData($orgId, $product);
            }
        } elseif (!empty($body['title'])) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(ProductInterface::NAME, '%'.$body['title'].'%', 'like')
                ->addFilter(ProductInterface::VISIBILITY, Visibility::VISIBILITY_NOT_VISIBLE, 'neq')
                ->addFilter(ProductInterface::STATUS, Status::STATUS_ENABLED)
                ->addFilter(ProductInterface::TYPE_ID, $productType, 'in')
                ->addFilter("store_id", $store->getId())
                ->create();
            $products = $this->productRepository->getList($searchCriteria)->getItems();

            foreach ($products as $product) {
                $response[] = $this->getProductData($orgId, $product);
            }
        }

        return $response;
    }

    /**
     * @inheritdoc
     */
    public function getProductData(string $orgId, Product|ProductInterface $product): ProductsDataInterface
    {
        $productData = $this->productDataFactory->create();
        $typeId = $product->getTypeId();

        $store = $this->storeManager->getStore();
        $storeId = $store->getId();
        $stockItem = $this->stockItemRepository->get($product->getId());
        $productQty = $typeId == ConfigurableType::TYPE_CODE ? 0 : $this->getProductSalableQty($product->getSku());
        $manageStock = $stockItem->getManageStock();
        $management = "product";

        if ($typeId == ConfigurableType::TYPE_CODE) {
            $management = "variant";
        } elseif (!$manageStock) {
            $management = "bypass";
        }

        $imageUrl = $product->getData('small_image');
        if ($imageUrl === null || $imageUrl == 'no_selection') {
            $this->appEmulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
            $imageUrl = $this->imageHelper->getDefaultPlaceholderUrl('thumbnail');
            $this->appEmulation->stopEnvironmentEmulation();
        } else {
            $imageUrl = $product->getMediaConfig()->getMediaUrl($imageUrl);
        }

        $productData->setOrganizationId($orgId)
            ->setTitle($product->getName())
            ->setBodyHtml($product->getDescription())
            ->setVendor("")
            ->setProductType('physical')
            ->setInventoryQuantity($productQty)
            ->setInventoryManagement($management)
            ->setImgSrc($imageUrl)
            ->setExternalId($product->getId())
            ->setIsSubscriptionOnly(false)
            ->setOptions($this->getProductOptions($product))
            ->setVariants($this->getProductVariants($product))
            ->setImages($this->getProductImages($product));

        return $productData;
    }

    /**
     * @param Product|ProductInterface $product
     * @return array
     */
    private function getProductOptions(Product|ProductInterface $product): array
    {
        $options = [];
        if ($product->getTypeId() == ConfigurableType::TYPE_CODE) {
            $productAttributeOptions = $product->getTypeInstance()->getConfigurableAttributes($product);

            foreach ($productAttributeOptions as $option) {
                $optionValues = [];
                foreach ($option->getOptions() as $index => $value) {
                    $optionValuesData = $this->optionValuesFactory->create();
                    $optionValuesData->setName($value['store_label'])
                        ->setExternalId($value['value_index'])
                        ->setPosition($index);
                    $optionValues[] = $optionValuesData;
                }

                $optionsData = $this->optionsFactory->create();
                $optionsData->setName($option->getLabel())
                    ->setPosition($option->getPosition())
                    ->setExternalId($option->getId())
                    ->setOptionValue($optionValues);

                $options[] = $optionsData;
            }
        }
        return $options;
    }

    /**
     * @param Product|ProductInterface $product
     * @return array|ProductVariantsDataInterface[]
     * @throws NoSuchEntityException
     */
    private function getProductVariants(Product|ProductInterface $product): array
    {
        $productVariants = [];
        $store = $this->storeManager->getStore();
        $currency = $store->getCurrentCurrencyCode();

        if ($product->getTypeId() == ConfigurableType::TYPE_CODE) {
            $variants = $product->getTypeInstance()->getUsedProducts($product);
            $counter = 1;

            $attributes = [];
            $productAttributeOptions = $product->getTypeInstance()->getConfigurableAttributes($product);
            foreach ($productAttributeOptions as $option) {
                $eavAttribute = $option->getProductAttribute();
                $attributes[] = $eavAttribute->getAttributeCode();
            }

            foreach ($variants as $variant) {
                $variantsData = $this->getVariantsData($variant, $currency, $counter, $attributes);
                $productVariants[] = $variantsData;
                $counter++;
            }
        }
        return $productVariants ?: [$this->getVariantsData($product, $currency, 1)];
    }

    /**
     * Get product variants data
     *
     * @param Product|ProductInterface $variant
     * @param string $currency
     * @param int $position
     * @param array $attributes
     * @return ProductVariantsDataInterface
     */
    private function getVariantsData(
        Product|ProductInterface $variant,
        string $currency,
        int $position,
        array $attributes = []
    ): ProductVariantsDataInterface {
        $typeId = $variant->getTypeId();
        $shippingRequired = !in_array($typeId, [ProductType::TYPE_VIRTUAL, DownloadableType::TYPE_DOWNLOADABLE]);
        $productQty = $typeId == ConfigurableType::TYPE_CODE ? 0 : $this->getProductSalableQty($variant->getSku());

        $pricesData = $this->variantsPricesFactory->create();
        $pricesData->setCurrency($currency)
            ->setPrice($variant->getPrice())
            ->setDiscountedPrice($variant->getSpecialPrice() ?: $variant->getPrice());

        $variantValues = [];
        foreach ($attributes as $attribute) {
            $attributeValue = $variant->getData($attribute);
            $variantValue = $this->variantValueFactory->create();
            $variantValue->setProductOptionValueId($attributeValue);
            $variantValues[] = $variantValue;
        }

        $variantsData = $this->productVariantsFactory->create();
        $variantsData->setExternalId($variant->getEntityId())
            ->setInventoryQuantity($productQty)
            ->setPosition($position)
            ->setExternalSku($variant->getSku())
            ->setRequiresShipping($shippingRequired)
            ->setTaxable($variant->getTaxClassId() > 0)
            ->setImages($this->getProductImages($variant))
            ->setOptionValues($variantValues)
            ->setPrices([$pricesData]);
        return $variantsData;
    }

    /**
     * Get product images
     *
     * @param Product|ProductInterface $product
     * @return array
     */
    private function getProductImages(Product|ProductInterface $product): array
    {
        $images = $product->getMediaGalleryImages();
        $imagesData = [];

        foreach ($images as $image) {
            $imageData = $this->productImgDataFactory->create();
            $imageData->setPosition($image->getPosition())
                ->setSrc($image->getUrl())
                ->setExternalId($image->getValueId());

            $imagesData[] = $imageData;
        }
        return $imagesData;
    }

    /**
     * Get product salable qty by SKU
     *
     * @param string $sku
     * @return float|int
     */
    private function getProductSalableQty(string $sku): float|int
    {
        try {
            $stockData = $this->getSalableQtyDataBySku->execute($sku);
            $salableQty = array_sum(array_column($stockData, 'qty'));
        } catch (\Exception $e) {
            $salableQty = 0;
        }
        return $salableQty;
    }
}
