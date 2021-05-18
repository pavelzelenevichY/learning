<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Model;

use Magento\Framework\Filesystem;
use Codifi\Training\Model\NoteRepository;
use Magento\Framework\File\Csv;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class ExportCsv
 * @package Codifi\CustomerRequest\Model
 */
class ExportCsv
{
    /**
     * Filesystem
     *
     * @var Filesystem
     */
    protected $fileSystem;

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
     * TimezoneInterface
     *
     * @var TimezoneInterface
     */
    private $timeZoneInterface;

    /**
     * New directory
     */
    private $newDirectory;

    /**
     * ExportCsv constructor.
     *
     * @param Filesystem $fileSystem
     * @param NoteRepository $noteRepository
     * @param Csv $csvProcessor
     * @param DirectoryList $directoryList
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param TimezoneInterface $timeZoneInterface
     * @throws FileSystemException
     */
    public function __construct(
        Filesystem $fileSystem,
        NoteRepository $noteRepository,
        Csv $csvProcessor,
        DirectoryList $directoryList,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        TimezoneInterface $timeZoneInterface
    ) {
        $this->noteRepository = $noteRepository;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timeZoneInterface = $timeZoneInterface->date();
        $this->newDirectory = $fileSystem->getDirectoryWrite(DirectoryList::VAR_DIR);
    }

    /**
     * Pack customer notes to archive and delete from table
     *
     * @param int $period
     * @return string
     * @throws NoSuchEntityException
     */
    public function export(int $period): string
    {
        $currentDateTime = $this->timeZoneInterface->format('Y_m_d H:m:s');

        $date = strtotime($currentDateTime . "-1 $period");
        $newDate = date('Y-m-d H:m:s', $date);

        $updatedAt = $newDate;

        $this->filterBuilder->setField('updated_at');
        $this->filterBuilder->setConditionType('to');
        $this->filterBuilder->setValue($updatedAt);
        $filter = $this->filterBuilder->create();

        $this->searchCriteriaBuilder->addFilter($filter);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        $noteList = $this->noteRepository->getList($searchCriteria);

        $noteListItems = $noteList->getItems();

        $content[] = [
            'note_id' => __('Note ID'),
            'customer_id' => __('Customer ID'),
            'created_at' => __('Created At'),
            'created_by' => __('Created By'),
            'note' => __('Note'),
            'updated_at' => __('Updated At'),
            'updated_by' => __('Updated By'),
            'autocomplete' => __('Autocomplete')
        ];

        foreach ($noteListItems as $item) {
            $note = $item->getData();
            $content[] = $note;
            $this->noteRepository->deleteById($note['note_id']);
        }

        try {
            $currentDateForName = $this->timeZoneInterface->format('Y_m_d');

            $this->newDirectory->create('/archive/');

            $fileName = "customer_note_$currentDateForName.csv";
            $filePath = $this->directoryList->getPath(DirectoryList::VAR_DIR) . "/archive/" . $fileName;

            $this->csvProcessor->setEnclosure('"');
            $this->csvProcessor->setDelimiter(',');
            $this->csvProcessor->saveData($filePath, $content);

            $message = 'success';
        } catch (FileSystemException $exception) {
            $message = $exception->getMessage();
        }

        return $message;
    }
}
