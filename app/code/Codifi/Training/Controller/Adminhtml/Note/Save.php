<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\Training\Controller\Adminhtml\Note;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\Json;
use Codifi\Training\Model\NoteRepository;
use Codifi\Training\Model\CustomerNoteFactory;
use Magento\Backend\Model\Auth\Session;
use Exception;

/**
 * Class Save
 * @package Codifi\Training\Controller\Adminhtml\Note
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
     * Note repository
     *
     * @var NoteRepository
     */
    private $noteRepository;

    /**
     * Customer note factory
     *
     * @var CustomerNoteFactory
     */
    private $noteFactory;

    /**
     * Auth session
     *
     * @var Session
     */
    private $authSession;

    /**
     * Backend session from context
     */
    private $backendSession;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param CustomerNoteFactory $noteFactory
     * @param NoteRepository $noteRepository
     * @param JsonFactory $jsonFactory
     * @param Session $authSession
     */
    public function __construct(
        Context $context,
        CustomerNoteFactory $noteFactory,
        NoteRepository $noteRepository,
        JsonFactory $jsonFactory,
        Session $authSession
    ) {
        $this->noteFactory = $noteFactory;
        $this->noteRepository = $noteRepository;
        $this->jsonFactory = $jsonFactory;
        $this->authSession = $authSession;
        $this->backendSession = $context->getSession();
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
        $request = $this->getRequest();

        $admin = $this->authSession->getUser();
        $adminId = $admin->getId();
        $customerData = $this->backendSession->getCustomerData();
        $customerId = (int)$customerData['account']['id'] ?? 0;

        $success = true;
        $noteText = $request->getParam('note');
        if ($noteText) {
            try {
                $id = $request->getParam('note_id');
                if ($id) {
                    $note = $this->noteRepository->getById($id);
                    $note->setUpdatedBy($adminId);
                } else {
                    $data = [
                        'customer_id' => $customerId,
                        'created_by' => $adminId,
                    ];
                    $note = $this->noteFactory->create();
                    $note->setData($data);
                }
                $message = __('Note has been successfully saved!');
                $note->setNote($noteText);
                $this->noteRepository->save($note);
            } catch (LocalizedException $exception) {
                $message = $exception->getMessage();
                $success = false;
            }
        } else {
            $message = __('Note text is missed.');
            $success = false;
        }

        $resultJson = $this->jsonFactory->create();
        $resultJson->setData([
            'success' => $success,
            'message' => $message
        ]);

        return $resultJson;
    }
}
