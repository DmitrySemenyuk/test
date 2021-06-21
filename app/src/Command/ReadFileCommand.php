<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\GoogleSpreadSheetClient;
use App\Downloader\XmlDownloader;
use App\Mapper\ProductMapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \SimpleXMLElement;

class ReadFileCommand extends Command
{
    protected static $defaultName = 'app:read-file';

    public function __construct(
        protected ProductMapper $productMapper,
        public GoogleSpreadSheetClient $googleSpreadSheetClient,
        protected LoggerInterface $logger,
        protected XmlDownloader $xmlDownloader,
        protected string $xmlSrc,
        string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument('src', InputArgument::OPTIONAL, 'Source to xml document');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $src = $input->getArgument('src');
            $this->tryToDownloadFIle($output, $src);

            $rows = $this->getRowsFromFile();
            $output->writeln('Xml file was read.');

            $products = $this->transformRawData($rows);
            $output->writeln('Rows were transformed before save.');

            $this->googleSpreadSheetClient->saveSheet($products);
            $output->writeln('Spreadsheet was saved.');
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            $output->writeln($e->getMessage());
        }

        return Command::SUCCESS;
    }

    private function tryToDownloadFIle(OutputInterface $output, ?string $src): void
    {
        if ($src) {
            $this->xmlDownloader->download($src);
            $output->writeln('File was downloaded.');
        }
    }

    private function getRowsFromFile(): SimpleXMLElement
    {
        $xmlStr = file_get_contents($this->xmlSrc);

        return new SimpleXMLElement($xmlStr);
    }

    private function transformRawData(SimpleXMLElement $rows): array
    {
        $products = [];
        foreach ($rows as $row) {
            $products[] = $this->productMapper->transformXmlToProduct($row);
        }

        return $products;
    }
}
