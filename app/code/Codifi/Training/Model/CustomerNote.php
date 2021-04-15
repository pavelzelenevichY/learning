<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Model;

use Codifi\Training\Api\Data\NoteInterface;
use Magento\Framework\Model\AbstractModel;
use Codifi\Training\Model\ResourceModel\CustomerNote as ResourceModel;

/**
 * Class CustomerNote
 * @package Codifi\Training\Model
 */
class CustomerNote extends AbstractModel implements NoteInterface
{
    /**
     * CustomerNote constructor.
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * Get note id.
     *
     * @return int
     */
    public function getNoteId(): int
    {
        return $this->_getData(self::NOTE_ID);
    }

    /**
     * Set note id.
     *
     * @param int $noteId
     * @return void
     */
    public function setNoteId($noteId): void
    {
        $this->setData(self::NOTE_ID, $noteId);
    }

    /**
     * Get note.
     *
     * @return string
     */
    public function getNote(): string
    {
        return $this->_getData(self::NOTE);
    }

    /**
     * Set note.
     *
     * @param string $noteText
     * @return void
     */
    public function setNote($noteText): void
    {
        $this->setData(self::NOTE, $noteText);
    }

    /**
     * Get autocomplete value.
     *
     * @return int
     */
    public function getAutocomplete(): int
    {
        return $this->_getData(self::AUTOCOMPLETE);
    }

    /**
     * Set autocomplete value.
     *
     * @param $autocomplete
     */
    public function setAutocomplete($autocomplete)
    {
        $this->setData(self::AUTOCOMPLETE, $autocomplete);
    }

    /**
     * Get customer id.
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->_getData(self::CUSTOMER_ID);
    }

    /**
     * Set customer id.
     *
     * @param $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get created at.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->_getData(self::CREATED_AT);
    }

    /**
     * Set created at.
     *
     * @param string $createdAt
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get created by.
     *
     * @return int
     */
    public function getCreatedBy(): int
    {
        return $this->_getData(self::CREATED_BY);
    }

    /**
     * Set created by.
     *
     * @param int $createdBy
     */
    public function setCreatedBy(int $createdBy)
    {
        $this->setData(self::CREATED_BY, $createdBy);
    }

    /**
     * Get updated at.
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->_getData(self::UPDATED_AT);
    }

    /**
     * Set updated at.
     *
     * @param string $updatedAt
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get updated by.
     *
     * @return int
     */
    public function getUpdatedBy(): int
    {
        return $this->_getData(self::UPDATED_BY);
    }

    /**
     * Set updated by.
     *
     * @param int $updatedBy
     */
    public function setUpdatedBy(int $updatedBy)
    {
        $this->setData(self::UPDATED_BY, $updatedBy);
    }
}
