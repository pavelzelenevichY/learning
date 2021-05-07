<?php


namespace Codifi\CustomerRequest\Controller\Request;

use Magento\Framework\App\Action\Action;
use Codifi\Training\Model\CustomerNoteFactory;
use Codifi\Training\Model\NoteRepository;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Escaper;

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

    public function execute()
    {
        $customerId = $this->getRequest()->getParam('customer_id');
        $customerEmail = $this->getRequest()->getParam('email_address');
        $message = $this->getRequest()->getParam('customer_messsage');
        $customerName = $this->getRequest()->getParam('customer_name');

        $storeName = $this->storeManager->getStore()->getFrontendName();

        $note = "Customer sent request from $storeName";

        $sender = [
            'name' => $this->escaper->escapeHtml($customerName),
            'email' => $this->escaper->escapeHtml($customerEmail),
        ];

        $transport = $this->transportBuilder
            ->setTemplateIdentifier('email_request_template')
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars([
                'customerNameVar' => $customerName,
                'customerEmailVar' => $customerEmail,
                'messageVar'  => $message,
            ])
            ->setFromByScope($sender)
            ->addTo('support@example.com')
            ->getTransport();
        $transport->sendMessage();

        $customerNoteModel = $this->customerNoteFactory->create();

        $message = "An error occurred while processing your form. Please try again later.";

        if ($customerId) {
            if ($note) {
                try {
                    $customerNoteModel->setData([
                        'customer_id' => $customerId,
                        'note' => $note,
                        'autocomplete' => 1
                    ]);
                    $this->customerNoteRepository->save($customerNoteModel);
                    $message = "Thanks for contacting us with your request. We'll respond to you very soon.";
                } catch (LocalizedException $exception) {
                    $errorMessage = $exception->getMessage();
                }
            }
        }

        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setPath('customer/request/index');

        return $redirect;

    }
}
