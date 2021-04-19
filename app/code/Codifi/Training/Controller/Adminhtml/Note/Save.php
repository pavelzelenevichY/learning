<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Controller\Adminhtml\Note;

use Codifi\Training\Model\AdminSessionManagement;
use Codifi\Training\Model\CustomerNoteFactory;
use Codifi\Training\Model\ResourceModel\CustomerNote as CustomerNoteResource;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Json;
use Exception;

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
     * Admin session management
     *
     * @var AdminSessionManagement
     */
    private $adminSessionManagement;

    /**
     * Json factory
     *
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param CustomerNoteFactory $customerNoteFactory
     * @param CustomerNoteResource $customerNoteResource
     * @param AdminSessionManagement $adminSessionManagement
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        CustomerNoteFactory $customerNoteFactory,
        CustomerNoteResource $customerNoteResource,
        AdminSessionManagement $adminSessionManagement,
        JsonFactory $jsonFactory
    ) {
        $this->customerNoteFactory = $customerNoteFactory;
        $this->customerNoteResource = $customerNoteResource;
        $this->adminSessionManagement = $adminSessionManagement;
        $this->jsonFactory = $jsonFactory;
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

        $ids = $this->adminSessionManagement->getAdminId();

        $adminId = $ids['admin_id'];
        $customerId = $ids['customer_id'];

        $request = $this->getRequest();
        $noteId = $request->getParam('note_id');
        $note = $request->getParam('note');
        $createdAt = $request->getParam('created_at');
        $createdBy = $request->getParam('created_by');

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
}
