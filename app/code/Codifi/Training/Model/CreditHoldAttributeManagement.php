<?php
/**
 * Codifi_Training
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\Training\Model;

use Codifi\Training\Api\CreditHoldAttributeManagementInterface;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Exception;
use Codifi\Training\Model\NoteRepository;
use Codifi\Training\Model\AttributeManagementResponseFactory;
use Codifi\Training\Api\AttributeManagementResponseInterface;
use Codifi\Training\Setup\Patch\Data\AddCustomerAttributeCreditHold;
use \Psr\Log\LoggerInterface;

/**
 * Class CreditHoldAttributeManagement
 * @package Codifi\Training\Model
 */
class CreditHoldAttributeManagement implements CreditHoldAttributeManagementInterface
{
    /**
     * Customer repository
     *
     * @var CustomerRepository
     */
    private $customerRepository;

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
     * Logger interface
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Response factory
     *
     * @var AttributeManagementResponseFactory
     */
    private $responseFactory;

    /**
     * CreditHoldAttributeManagement constructor.
     *
     * @param CustomerRepository $customerRepository
     * @param NoteRepository $noteRepository
     * @param CustomerNoteFactory $noteFactory
     * @param AttributeManagementResponseFactory $responseFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerRepository $customerRepository,
        NoteRepository $noteRepository,
        CustomerNoteFactory $noteFactory,
        AttributeManagementResponseFactory $responseFactory,
        LoggerInterface $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->noteRepository = $noteRepository;
        $this->noteFactory = $noteFactory;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    /**
     * Update attribute
     *
     * @param int $customerId
     * @param int $creditHold
     * @return AttributeManagementResponseInterface
     */
    public function updateAttribute(int $customerId, int $creditHold): AttributeManagementResponseInterface
    {
        $status = AttributeManagementResponseInterface::STATUS_OK;
        try {
            $customer = $this->customerRepository->getById($customerId);
            $customer->setCustomAttribute(AddCustomerAttributeCreditHold::ATTRIBUTE_CODE, $creditHold);
            $this->customerRepository->save($customer);
            $data = [
                'note' => __('Credit hold status has been updated via API request.'),
                'customer_id' => $customerId,
                'autocomplete' => 1
            ];
            $note = $this->noteFactory->create();
            $note->setData($data);
            $this->noteRepository->save($note);
            $message = '';
        } catch (Exception $e) {
            $message = $e->getMessage();
            $this->logger->error($message);
            $status = AttributeManagementResponseInterface::STATUS_FAILED;
        }

        $response = $this->responseFactory->create();
        $response->setStatus($status);
        $response->setMessage($message);

        return $response;
    }
}
