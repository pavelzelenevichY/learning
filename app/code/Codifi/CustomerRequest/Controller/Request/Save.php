<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Controller\Request;

use Magento\Framework\App\Action\Action;
use Codifi\Training\Model\CustomerNoteFactory;
use Codifi\Training\Model\NoteRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\App\Area;
use Magento\Store\Model\Store;
use Magento\Framework\Controller\ResultFactory;
use Codifi\CustomerRequest\Helper\Config;

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
     * Escaper
     *
     * @var Escaper
     */
    private $escaper;

    /**
     * Store manager interface
     *
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
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
//     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        CustomerNoteFactory $customerNoteFactory,
        NoteRepository $customerNoteRepository,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        Escaper $escaper,
        Config $config
    ) {
        $this->customerNoteFactory = $customerNoteFactory;
        $this->customerNoteRepository = $customerNoteRepository;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
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

        try {
            $store = $this->storeManager->getStore();
            $storeName = $store->getFrontendName();

            $format = 'Customer sent request from %s';
            $note = sprintf($format, $storeName);

            $sender = [
                'name' => $customerName,
                'email' => $customerEmail
            ];

            $transportTemplate = $this->transportBuilder->setTemplateIdentifier('email_request_template');
            $transportOptions = $transportTemplate->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => Store::DEFAULT_STORE_ID,
                ]
            );
            $transportVars = $transportOptions->setTemplateVars([
                'customerNameVar' => $customerName,
                'customerEmailVar' => $customerEmail,
                'messageVar' => $message,
            ]);
            $transportVarsInScope = $transportVars->setFromByScope($sender);
            $transportAddress = $transportVarsInScope->addTo($this->config->getSupportEmailPath());
            $transport = $transportAddress->getTransport();
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

        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('customer/request/index');

        return $redirect;
    }

    private function validate($request)
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
