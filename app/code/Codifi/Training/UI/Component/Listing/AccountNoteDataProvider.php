<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\UI\Component\Listing;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Codifi\Training\Model\NoteRepository;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class AccountNoteDataProvider
 * @package Codifi\Training\UI\Component\Listing
 */
class AccountNoteDataProvider extends AbstractDataProvider
{
    /**
     * Note repository
     *
     * @var NoteRepository
     */
    private $noteRepository;

    /**
     * Result interface
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Filter builder
     *
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * Search criteria builder
     *
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * AccountNoteDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param RequestInterface $request
     * @param NoteRepository $noteRepository
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        RequestInterface $request,
        NoteRepository $noteRepository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->noteRepository = $noteRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        $customerId = $this->request->getParam('parent_id');

        $this->filterBuilder->setField('customer_id');
        $this->filterBuilder->setValue($customerId);
        $filter = $this->filterBuilder->create();

        $this->searchCriteriaBuilder->addFilter($filter);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $noteList = $this->noteRepository->getList($searchCriteria);
        $noteListItems = $noteList->getItems();

        $returnData['items'] = [];

        foreach ($noteListItems as $item) {
            $returnData['items'][] = $item->getData();
        }
        $returnData['totalRecords'] = count($returnData['items']);

        return $returnData;
    }
}
