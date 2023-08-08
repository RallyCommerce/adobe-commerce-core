<?php
declare(strict_types=1);

namespace Rally\Checkout\Model;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Store\Model\StoreManagerInterface;
use Rally\Checkout\Api\CategoriesManagerInterface;
use Rally\Checkout\Api\Data\CategoriesDataInterface;
use Rally\Checkout\Api\Data\CategoriesDataInterfaceFactory;
use Rally\Checkout\Api\Service\RequestValidatorInterface;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\CategoryListInterface;
use Magento\Catalog\Model\Category\Image as CategoryImage;

/**
 * Checkout CategoriesManager model.
 * @api
 */
class CategoriesManager implements CategoriesManagerInterface
{
    public function __construct(
        public StoreManagerInterface $storeManager,
        public CategoriesDataInterfaceFactory $categoriesDataFactory,
        public RequestValidatorInterface $requestValidator,
        public Request $request,
        public CategoryRepositoryInterface $categoryRepository,
        public SearchCriteriaBuilder $searchCriteriaBuilder,
        public CategoryListInterface $categoryList,
        public CategoryImage $image
    ) {
    }

    /**
     * @inheritdoc
     */
    public function get(string $orgId): array
    {
        $this->requestValidator->validate();
        $body = $this->request->getBodyParams();
        $response = [];

        if (!empty($body['category_id'])) {
            $store = $this->storeManager->getStore();
            $storeId = $store->getId();

            try {
                $category = $this->categoryRepository->get($body['category_id'], $storeId);
            } catch (\Exception $e) {
                return $response;
            }

            if ($category->getIsActive()) {
                $response[] = $this->getCategoryData($orgId, $category);
            }
        } elseif (!empty($body['title'])) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter(CategoryInterface::KEY_NAME, '%'.$body['title'].'%', 'like')
                ->addFilter(CategoryInterface::KEY_IS_ACTIVE, '1')
                ->create();
            $items = $this->categoryList->getList($searchCriteria)->getItems();

            foreach ($items as $category) {
                $response[] = $this->getCategoryData($orgId, $category);
            }
        }

        return $response;
    }

    /**
     * Get category data
     *
     * @param string $orgId
     * @param $category
     * @return CategoriesDataInterface
     * @throws LocalizedException
     */
    private function getCategoryData(string $orgId, $category): CategoriesDataInterface
    {
        $categoryData = $this->categoriesDataFactory->create();
        $categoryImage = $this->image->getUrl($category);
        $categoryData->setOrganizationId($orgId)
            ->setTitle($category->getName())
            ->setExternalId($category->getId())
            ->setImageUrl($categoryImage);

        return $categoryData;
    }
}
