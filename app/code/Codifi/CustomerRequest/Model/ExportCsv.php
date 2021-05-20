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
use Magento\Framework\Stdlib\DateTime\DateTime;

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
     * TimezoneInterface
     *
     * @var TimezoneInterface
     */
    private $timeZoneInterface;

    /**
     * @var DateTime
     */
    private $dateTime;

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
        TimezoneInterface $timeZoneInterface,
        DateTime $dateTime
    ) {
        $this->noteRepository = $noteRepository;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->timeZoneInterface = $timeZoneInterface->date();
        $this->dateTime = $dateTime;
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
        $format = "-%s months";
        $period = sprintf($format, $period);
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
            $currentDateForName = $this->dateTime->date('Y_m_d');

            $this->newDirectory->create('/archive/');

            if ($this->newDirectory->isDirectory('/archive/')) {

                $format = 'customer_note_%s.csv';
                $fileName = sprintf($format, $currentDateForName);
                $filePath = $this->directoryList->getPath(DirectoryList::VAR_DIR) . "/archive/" . $fileName;

                $this->csvProcessor->setEnclosure('"');
                $this->csvProcessor->setDelimiter(',');
                $this->csvProcessor->saveData($filePath, $content);

                $message = 'success';
                foreach ($noteListItems as $item) {
                    $note = $item->getData();
                    $this->noteRepository->deleteById($note['note_id']);
                }
            } else {
                $message = 'Something went wrong, try again later.';
            }
        } catch (FileSystemException $exception) {
            $message = $exception->getMessage();
        }

        return $message;
    }
}
