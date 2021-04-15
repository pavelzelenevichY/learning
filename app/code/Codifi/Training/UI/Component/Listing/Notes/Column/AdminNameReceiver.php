<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\UI\Component\Listing\Notes\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\User\Model\ResourceModel\User\CollectionFactory;

/**
 * Class AdminNameReceiver
 * @package Codifi\Training\UI\Component\Listing\Notes\Column
 */
class AdminNameReceiver extends Column
{
    /**
     * Admin collection factory
     *
     * @var CollectionFactory
     */
    private $userCollectionFactory;

    /**
     * AdminNameReceiver constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CollectionFactory $userCollectionFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CollectionFactory $userCollectionFactory,
        array $components = [],
        array $data = []
    ) {
        $this->userCollectionFactory = $userCollectionFactory;
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item[$fieldName])) {
                    $item[$fieldName] = $this->getAdminName((int)$item[$fieldName]);
                } else {
                    $item[$fieldName] = '';
                }
            }
        }

        return $dataSource;
    }

    /**
     * Get admin name by id.
     *
     * @param int $userId
     * @return string
     */
    private function getAdminName($userId): string
    {
        $userName = '';
        $userCollection = $this->userCollectionFactory->create();
        $users = $userCollection->getData();
        foreach ($users as $user) {
            if ((int)$user['user_id'] === $userId) {
                $userName = $user['firstname'] . ' ' . $user['lastname'] . ' (ID: ' . $user['user_id'] . ')';
                break;
            }
        }

        return $userName;
    }
}
