<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Controller\Adminhtml\Note;

use Codifi\Training\Model\CustomerNoteFactory;
use Codifi\Training\Model\ResourceModel\CustomerNote as CustomerNoteResource;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Json;
use Magento\Backend\Model\Auth\Session;
use Exception;

/**
 * Class Save
 * @package Codifi\Training\Controller\Adminhtml\Note
 */
class Save extends Action
{
    /**
     * Customer note factory
     *
     * @var CustomerNoteFactory
     */
    private $customerNoteFactory;

    /**
     * Customer note resource model
     *
     * @var CustomerNoteResource
     */
    private $customerNoteResource;

    /**
     * Json factory
     *
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * Auth session
     *
     * @var Session
     */
    private $authSession;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param CustomerNoteFactory $customerNoteFactory
     * @param CustomerNoteResource $customerNoteResource
     * @param JsonFactory $jsonFactory
     * @param Session $authSession
     */
    public function __construct(
        Context $context,
        CustomerNoteFactory $customerNoteFactory,
        CustomerNoteResource $customerNoteResource,
        JsonFactory $jsonFactory,
        Session $authSession
    ) {
        $this->customerNoteFactory = $customerNoteFactory;
        $this->customerNoteResource = $customerNoteResource;
        $this->jsonFactory = $jsonFactory;
        $this->authSession = $authSession;
        parent::__construct($context);
    }

    /**
     * Execute function
     *
     * @return Json
     * @throws Exception
     */
    public function execute(): Json
    {
        $customerNoteModel = $this->customerNoteFactory->create();
        $resultJson = $this->jsonFactory->create();

        $adminId = $this->getAdminId();

        $request = $this->getRequest();
        $noteId = $request->getParam('note_id');
        $note = $request->getParam('note');
        $createdAt = $request->getParam('created_at');
        $createdBy = $request->getParam('created_by');
        $customerId = $request->getParam('parent_id');

        if ($note) {
            if (!$noteId) {
                $data = [
                    'customer_id' => $customerId,
                    'note' => $note,
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                    'autocomplete' => 0
                ];
                $resultData = [
                    'success' => true,
                    'message' => __('Note has been successfully saved!'),
                    'data' => [
                        'note_id' => $noteId
                    ]
                ];
            } else {
                $data = [
                    'note_id' => $noteId,
                    'customer_id' => $customerId,
                    'note' => $note,
                    'created_at' => $createdAt,
                    'created_by' => $createdBy,
                    'updated_by' => $adminId,
                    'autocomplete' => 0
                ];
                $resultData = [
                    'success' => true,
                    'message' => __('Note has been successfully updated!'),
                    'data' => [
                        'note_id' => $noteId
                    ]
                ];
            }

            try {
                $customerNoteModel->setData($data);
                $this->customerNoteResource->save($customerNoteModel);
            } catch (LocalizedException $exception) {
                $resultData = [
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'data' => [
                        'note_id' => ''
                    ]
                ];
            }
        } else {
            $resultData = [
                'success' => false,
                'message' => __('Note text is missed.'),
                'data' => [
                    'note_id' => ''
                ]
            ];
        }
        $resultJson->setData($resultData);

        return $resultJson;
    }

    /**
     * Get admin id
     *
     * @return int
     */
    public function getAdminId(): int
    {
        $admin = $this->authSession->getUser();

        return (int)$admin->getId() ?? 0;
    }
}
