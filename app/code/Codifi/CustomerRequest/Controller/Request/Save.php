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
     * Save constructor.
     *
     * @param Context $context
     * @param CustomerNoteFactory $customerNoteFactory
     * @param NoteRepository $customerNoteRepository
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param Escaper $escaper
     */
    public function __construct(
        Context $context,
        CustomerNoteFactory $customerNoteFactory,
        NoteRepository $customerNoteRepository,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        Escaper $escaper
    ) {
        $this->customerNoteFactory = $customerNoteFactory;
        $this->customerNoteRepository = $customerNoteRepository;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->escaper = $escaper;
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
        $customerId = $request->getParam('customer_id');
        $customerEmail = $request->getParam('email_address');
        $message = $request->getParam('customer_messsage');
        $customerName = $request->getParam('customer_name');

        if ($customerId && $customerEmail && $message && $customerName) {

            $store = $this->storeManager->getStore();
            $storeName = $store->getFrontendName();

            $note = "Customer sent request from $storeName";

            $sender = [
                'name' => $this->escaper->escapeHtml($customerName),
                'email' => $this->escaper->escapeHtml($customerEmail),
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier('email_request_template')
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'customerNameVar' => $customerName,
                    'customerEmailVar' => $customerEmail,
                    'messageVar' => $message,
                ])
                ->setFromByScope($sender)
                ->addTo('support@example.com')
                ->getTransport();
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
                $this->messageManager->addErrorMessage(Config::ERROR_MESSAGE);
            }
        } else {
            $this->messageManager->addErrorMessage(Config::ERROR_MESSAGE);
        }

        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('customer/request/index');

        return $redirect;
    }
}
