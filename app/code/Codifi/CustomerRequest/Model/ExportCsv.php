<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Codifi\Training\Model\NoteRepository;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Codifi\CustomerRequest\Helper\Config;
use Exception;

/**
 * Class ExportCsv
 * @package Codifi\CustomerRequest\Model
 */
class ExportCsv
{
    /**
     * Customer note archive path
     */
    const CUSTOMER_NOTE_ARCHIVE_PATH = '/archive/';

    /**
     * Filesystem
     *
     * @var Filesystem
     */
    private $fileSystem;

    /**
     * Note repository
     *
     * @var NoteRepository
     */
    private $noteRepository;

    /**
     * Csv processor
     *
     * @var Csv
     */
    private $csvProcessor;

    /**
     * Directory list
     *
     * @var DirectoryList
     */
    private $directoryList;

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
     * Datetime
     *
     * @var DateTime
     */
    private $dateTime;

    /**
     * ExportCsv constructor.
     *
     * @param Filesystem $fileSystem
     * @param NoteRepository $noteRepository
     * @param Csv $csvProcessor
     * @param DirectoryList $directoryList
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param DateTime $dateTime
     */
    public function __construct(
        Filesystem $fileSystem,
        NoteRepository $noteRepository,
        Csv $csvProcessor,
        DirectoryList $directoryList,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DateTime $dateTime
    ) {
        $this->fileSystem = $fileSystem;
        $this->noteRepository = $noteRepository;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->dateTime = $dateTime;
    }

    /**
     * Pack customer notes to archive and delete from table
     *
     * @param int $period
     * @return string
     * @throws LocalizedException
     */
    public function export(int $period): string
    {
        $period = sprintf("-%s months", $period);
        $newDate = $this->dateTime->date('Y-m-d H:m:s', strtotime($period));

        $this->filterBuilder->setField('updated_at');
        $this->filterBuilder->setConditionType('to');
        $this->filterBuilder->setValue($newDate);
        $filter = $this->filterBuilder->create();

        $this->searchCriteriaBuilder->addFilter($filter);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $noteList = $this->noteRepository->getList($searchCriteria);

        $noteListItems = $noteList->getItems();

        $content = [];
        $isHeaderColsSet = false;
        foreach ($noteListItems as $item) {
            if (!$isHeaderColsSet) {
                $content[] = array_keys($item->getData());
                $isHeaderColsSet = true;
            }
            $content[] = $item->getData();
        }

        try {
            $newDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $newDirectory->create(self::CUSTOMER_NOTE_ARCHIVE_PATH);
        } catch (FileSystemException $exception) {
            throw new LocalizedException(
                __('Failed to create directory "%1"', self::CUSTOMER_NOTE_ARCHIVE_PATH)
            );
        }

        if (!$newDirectory->isWritable(self::CUSTOMER_NOTE_ARCHIVE_PATH)) {
            throw new LocalizedException(
                __('Directory "%1" is not writable.', self::CUSTOMER_NOTE_ARCHIVE_PATH)
            );
        }

        try {
            $currentDateForName = $this->dateTime->date('Y_m_d');

            $fileName = sprintf("customer_note_%s.csv", $currentDateForName);
            $filePath = $this->directoryList->getPath(DirectoryList::VAR_DIR) .
                self::CUSTOMER_NOTE_ARCHIVE_PATH . $fileName;

            $this->csvProcessor->setEnclosure('"');
            $this->csvProcessor->setDelimiter(',');
            $this->csvProcessor->appendData($filePath, $content, "w");

            $message = 'success';
            foreach ($noteListItems as $item) {
                $noteId = $item->getNoteId();
                $this->noteRepository->deleteById($noteId);
            }
        } catch (Exception $exception) {
            $message = $exception->getMessage();
        }

        return $message;
    }
}
