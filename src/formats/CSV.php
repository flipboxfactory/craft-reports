<?php

namespace flipbox\craft\reports\formats;

use flipbox\craft\reports\Reports;
use flipbox\craft\reports\sources\SourceInterface;
use Psr\Http\Message\StreamInterface;
use yii\web\Response;
use Zend\Diactoros\Stream;

class CSV extends AbstractFormat
{
    /**
     * The file extension
     */
    const EXTENSION = 'csv';

    /**
     * @var string delimiter between the CSV file cells.
     */
    public $delimiter = ',';

    /**
     * @var string the cell content enclosure.
     */
    public $enclosure = '"';

    /**
     * @var string
     */
    public $handle = 'csv';

    /**
     * @inheritDoc
     */
    public static function type(): string
    {
        return Reports::t('CSV');
    }

    /**
     * @return string
     */
    public static function fileExtension(): string
    {
        return static::EXTENSION;
    }

    /**
     * @inheritDoc
     */
    protected function modifyResponse(Response $response)
    {
        $response->format = $response::FORMAT_RAW;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(SourceInterface $source): StreamInterface
    {
        $csv = fopen('php://temp', 'r+');

        // Columns
        $columns = $source->getColumns();

        // Heading
        fputcsv($csv, $columns, $this->delimiter, $this->enclosure);

        // Invert columns and set empty values
        $keys = array_flip(array_keys($columns));

        // Rows
        foreach ($source->getData() as $row) {
            fputcsv($csv, array_replace($keys, $row), $this->delimiter, $this->enclosure);
        }

        return new Stream($csv);
    }
}
