<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Api;

use Codifi\Training\Api\Data\NoteInterface;
use Codifi\Training\Api\Data\NoteSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface NoteRepositoryInterface
 * @package Codifi\Training\Api
 */
interface NoteRepositoryInterface
{
    /**
     * Get note by id.
     *
     * @param int $noteId
     * @return NoteInterface
     */
    public function getById(int $noteId);

    /**
     * Save note.
     *
     * @param NoteInterface $note
     * @return NoteInterface
     */
    public function save(NoteInterface $note);

    /**
     * Delete note.
     *
     * @param NoteInterface $note
     * @return void
     */
    public function delete(NoteInterface $note);

    /**
     * Delete note by id.
     *
     * @param int $noteId
     * @return void
     */
    public function deleteById(int $noteId);

    /**
     * Get list.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
