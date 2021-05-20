<?php
/**
 * Codifi_CustomerRequest
 *
 * @copyright   Copyright (c) 2021 Codifi
 * @author      Pavel Zelenevich <pzelenevich@codifi.me>
 */

namespace Codifi\CustomerRequest\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Codifi\CustomerRequest\Model\ExportCsv;
use Magento\Framework\Exception\NoSuchEntityException;
use Codifi\CustomerRequest\Helper\Config;

/**
 * Class PackCustomerNotes
 * @package Codifi\CustomerRequest\Console
 */
class PackCustomerNotes extends Command
{
    /**
     * Period
     */
    const KEY_PERIOD = 'period';

    /**
     * Export to csv file
     *
     * @var ExportCsv
     */
    private $exportCsv;

    /**
     * Config
     *
     * @var Config
     */
    private $config;

    /**
     * PackCustomerNotes constructor.
     *
     * @param ExportCsv $exportCsv
     * @param Config $config
     */
    public function __construct(
        ExportCsv $exportCsv,
        Config $config
    ) {
        $this->exportCsv = $exportCsv;
        $this->config = $config;
        parent::__construct();
    }

    /**
     * Configures the current command
     */
    protected function configure()
    {
        $options = [
            new InputOption(
                self::KEY_PERIOD,
                null,
                null,
                'period'
            )
        ];

        $this->setName('codifi:archive:notes');
        $this->setDescription('Archive customer notes that haven\'t updates for N (period) months');
        $this->setDefinition($options);

        parent::configure();
    }

    /**
     * Executes the current command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this
     * @throws NoSuchEntityException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (gettype($input->getOption(self::KEY_PERIOD)) === "integer" || !$input->getOption(self::KEY_PERIOD)) {
            $period = $input->getOption(self::KEY_PERIOD) ?? $this->config->getPeriod();
            $export = $this->exportCsv->export($period);

            if ($export === 'success') {
                $format = "Customer notes that haven't updates %s months archived.";
                $output->writeln(sprintf($format, $period));
            } else {
                $output->writeln($export);
            }
        } else {
            $output->writeln("Input value must have integer type");
        }

        return $this;
    }
}
