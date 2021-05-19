<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

declare(strict_types=1);

namespace Codifi\CustomerRequest\Controller\Request;

use Magento\Framework\App\Action\Action;
use Codifi\Training\Model\CustomerNoteFactory;
use Codifi\Training\Model\NoteRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;
use Magento\Framework\Controller\ResultFactory;
use Codifi\CustomerRequest\Helper\Config;
use Magento\Framework\App\RequestInterface;

/**
 * Class Save
 * @package Codifi\CustomerRequest\Controller\Request
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
     * Customer note repository
     *
     * @var NoteRepository
     */
    private $customerNoteRepository;

    /**
     * Transport builder
     *
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * Store manager interface
     *
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Config
     *
     * @var Config
     */
    private $config;

    /**
     * Save constructor.
     *
     * @param Context $context
     * @param CustomerNoteFactory $customerNoteFactory
     * @param NoteRepository $customerNoteRepository
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     */
    public function __construct(
        Context $context,
        CustomerNoteFactory $customerNoteFactory,
        NoteRepository $customerNoteRepository,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        Config $config
    ) {
        $this->customerNoteFactory = $customerNoteFactory;
        $this->customerNoteRepository = $customerNoteRepository;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Execute function
     *
     * @return ResultInterface
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function execute(): ResultInterface
    {
        $request = $this->getRequest();

        $validate = $this->validate($request);

        $customerId = $validate['customerId'];
        $customerEmail = $validate['customerEmail'];
        $message = $validate['message'];
        $customerName = $validate['customerName'];

        if ($customerId && $customerEmail && $message && $customerName) {
            try {
                $store = $this->storeManager->getStore();

                $note = sprintf('Customer sent request from %s', $store->getFrontendName());

                $sender = [
                    'name' => $customerName,
                    'email' => $customerEmail
                ];

                $this->transportBuilder->setTemplateIdentifier('customer_request_template');
                $this->transportBuilder->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                );
                $this->transportBuilder->setTemplateVars([
                    'customerNameVar' => $customerName,
                    'customerEmailVar' => $customerEmail,
                    'messageVar' => $message,
                ]);
                $this->transportBuilder->setFromByScope($sender);
                $this->transportBuilder->addTo($this->config->getSupportEmailPath());
                $transport = $this->transportBuilder->getTransport();
                $transport->sendMessage();

                $customerNoteModel = $this->customerNoteFactory->create();

                try {
                    $customerNoteModel->setData([
                        'customer_id' => $customerId,
                        'note' => $note,
                        'autocomplete' => 1
                    ]);
                    $this->customerNoteRepository->save($customerNoteModel);
                    $this->messageManager->addSuccessMessage(Config::SUCCESS_MESSAGE);
                } catch (LocalizedException $exception) {
                    $this->messageManager->addErrorMessage(Config::ERROR_MESSAGE . $exception->getMessage());
                }
            } catch (NoSuchEntityException $exception) {
                throw $exception;
            }
        } else {
            $this->messageManager->addErrorMessage(Config::ERROR_MESSAGE);
        }

        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('customer/request/index');

        return $redirect;
    }

    /**
     * Validate request values
     *
     * @param RequestInterface $request
     * @return array
     */
    private function validate(RequestInterface $request): array
    {
        $customerId = $request->getParam('customer_id');
        $customerEmail = strip_tags($request->getParam('email_address'));
        $message = strip_tags($request->getParam('customer_messsage'));
        $customerName = strip_tags($request->getParam('customer_name'));

        return [
            'customerId' => $customerId,
            'customerEmail' => trim($customerEmail),
            'message' => trim($message),
            'customerName' => trim($customerName)
        ];
    }
}
