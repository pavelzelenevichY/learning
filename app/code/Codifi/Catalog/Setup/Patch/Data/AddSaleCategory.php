<?php
/**
 * Codifi_Catalog
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Catalog\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;


/**
 * Class AddSaleCategory
 * @package Codifi\CustomerRequest\Setup\Patch\Data
 */
class AddSaleCategory implements DataPatchInterface
{
    /**
     * Column name category id
     */
    const COLUMN_NAME_CATEGORY_ID = 'entity_id';

    /**
     * Category factory
     *
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * Category repository
     *
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * Url rewrite collection factory
     *
     * @var UrlRewriteCollectionFactory
     */
    private $urlRewriteCollectionFactory;

    /**
     * AddSaleCategory constructor.
     *
     * @param CategoryFactory $categoryFactory
     * @param CategoryRepository $categoryRepository
     * @param UrlRewriteCollectionFactory $urlRewriteCollectionFactory
     */
    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryRepository $categoryRepository,
        UrlRewriteCollectionFactory $urlRewriteCollectionFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
    }

    /**
     * Add new Sale category
     *
     * @throws CouldNotSaveException
     */
    public function apply(): void
    {
        $parentId = $this->getParentCategoryId();

        $data = [
            'parent_id'       => $parentId,
            'name'            => 'Sale',
            'is_active'       => 1,
            'is_anchor'       => 1,
            'include_in_menu' => 1,
            'url_key'         => 'sale',
        ];

        $category = $this->categoryFactory->create();
        $defaultSetId = $category->getDefaultAttributeSetId();
        $category->setData($data);
        $category->setAttributeSetId($defaultSetId);
        $this->categoryRepository->save($category);
    }

    /**
     * Get parent category id
     *
     * @return int
     */
    public function getParentCategoryId(): int
    {
        $urlCollection = $this->urlRewriteCollectionFactory->create();
        $urlCollection->addFilter('request_path', 'gear/fitness-equipment.html');
        $urlCollection->setPageSize(1);
        $firstItem = $urlCollection->getFirstItem();
        $id = $firstItem->getDataByKey(self::COLUMN_NAME_CATEGORY_ID);

        return (int)$id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
