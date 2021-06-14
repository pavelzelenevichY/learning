<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Codifi\Training\Api\Data\NoteInterface;
use Codifi\Training\Api\NoteRepositoryInterface;
use Codifi\Training\Model\CustomerNote;
use Codifi\Training\Model\CustomerNoteFactory;
use Codifi\Training\Model\ResourceModel\CustomerNote as CustomerNoteResourse;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Exception;
use Codifi\Training\Model\ResourceModel\CustomerNote\CollectionFactory;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Class NoteRepository
 * @package Codifi\Training\Model
 */
class NoteRepository implements NoteRepositoryInterface
{
    /**
     * Note factory.
     *
     * @var CustomerNoteFactory
     */
    private $noteFactory;

    /**
     * Note resourse.
     *
     * @var CustomerNoteResourse
     */
    private $noteResourse;

    /**
     * Search result interface factory.
     *
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * Collection processor interface.
     *
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * Customer note collection factory.
     *
     * @var CollectionFactory
     */
    private $customerNoteCollectionFactory;

    /**
     * NoteRepository constructor.
     *
     * @param CustomerNoteFactory $noteFactory
     * @param CustomerNoteResourse $noteResourse
     * @param SearchResultsInterfaceFactory $searchResultFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $customerNoteCollectionFactory
     */
    public function __construct(
        CustomerNoteFactory $noteFactory,
        CustomerNoteResourse $noteResourse,
        SearchResultsInterfaceFactory $searchResultFactory,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $customerNoteCollectionFactory
    ) {
        $this->noteFactory = $noteFactory;
        $this->noteResourse = $noteResourse;
        $this->searchResultFactory = $searchResultFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->customerNoteCollectionFactory = $customerNoteCollectionFactory;
    }

    /**
     * Get note by id.
     *
     * @param int $id
     * @return CustomerNote
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CustomerNote
    {
        $noteModel = $this->noteFactory->create();
        $this->noteResourse->load($noteModel, $id);
        if (!$noteModel->getId()) {
            throw new NoSuchEntityException(__('Unable to find note with ID "%1"', $id));
        }

        return $noteModel;
    }

    /**
     * Save note.
     *
     * @param NoteInterface $note
     * @return NoteInterface
     * @throws Exception
     */
    public function save(NoteInterface $note): NoteInterface
    {
        try {
            $this->noteResourse->save($note);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $note;
    }

    /**
     * Delete note.
     *
     * @param NoteInterface $note
     * @return NoteInterface
     * @throws Exception
     */
    public function delete(NoteInterface $note): NoteInterface
    {
        try {
            $this->noteResourse->delete($note);
        } catch (Exception $exception) {
            throw $exception;
        }

        return $note;
    }

    /**
     * Delete note by id.
     *
     * @param int $noteId
     * @throws NoSuchEntityException
     */
    public function deleteById(int $noteId): void
    {
        $this->delete($this->getById($noteId));
    }

    /**
     * Get list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        $collection = $this->customerNoteCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;
    }
}
