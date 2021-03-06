<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Controller\Note;

use Codifi\Training\Model\ResourceModel\CustomerNote as CustomerNoteResource;
use Codifi\Training\Model\CustomerNoteFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Codifi\Training\Model\CustomerSessionManagement;
use Exception;

/**
 * Class Save
 * @package Codifi\Training\Controller\Note
 */
class Save extends Action
{
    /**
     * Json factory
     *
     * @var JsonFactory
     */
    private $jsonFactory;

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
     * Customer session management
     *
     * @var CustomerSessionManagement
     */
    private $customerSessionManagement;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param CustomerNoteFactory $customerNoteFactory
     * @param CustomerNoteResource $customerNoteResource
     * @param CustomerSessionManagement $customerSessionManagement
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        CustomerNoteFactory $customerNoteFactory,
        CustomerNoteResource $customerNoteResource,
        CustomerSessionManagement $customerSessionManagement
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->customerNoteFactory = $customerNoteFactory;
        $this->customerNoteResource = $customerNoteResource;
        $this->customerSessionManagement = $customerSessionManagement;
    }

    /**
     * Execute function
     *
     * @return Json
     * @throws Exception
     */
    public function execute(): Json
    {
        $request = $this->getRequest();
        $note = $request->getParam('note');
        $customerId = $request->getParam('customer_id');

        $customerNoteModel = $this->customerNoteFactory->create();
        $resultJson = $this->jsonFactory->create();

        if ($customerId) {
            if ($note) {
                try {
                    $customerNoteModel->setData([
                        'customer_id' => $customerId,
                        'note' => $note,
                        'autocomplete' => 1
                    ]);
                    $this->customerNoteResource->save($customerNoteModel);
                    $response = $resultJson->setData([
                        'success' => true,
                        'message' => ''
                    ]);
                    $this->customerSessionManagement->setFlag();
                } catch (LocalizedException $exception) {
                    $response = $resultJson->setData([
                        'success' => false,
                        'message' => $exception->getMessage()
                    ]);
                }
            } else {
                $response = $resultJson->setData([
                    'success' => false,
                    'message' => __('Note text is missed.')
                ]);
            }
        } else {
            $response = $resultJson->setData([
                'success' => false,
                'message' => __('Customer id is missed.')
            ]);
        }

        return $response;
    }
}
