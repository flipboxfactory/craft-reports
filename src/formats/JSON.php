<?php

namespace flipbox\craft\reports\formats;

use Craft;
use flipbox\craft\reports\Reports;
use flipbox\craft\reports\sources\SourceInterface;
use Psr\Http\Message\StreamInterface;
use Violet\StreamingJsonEncoder\BufferJsonEncoder;
use Violet\StreamingJsonEncoder\JsonStream;
use yii\web\Response;

class JSON extends AbstractFormat
{
    /**
     * @var string the Content-Type header for the response
     */
    public $contentType = 'application/json';

    /**
     * @var string the XML encoding. If not set, it will use the value of [[Response::charset]].
     */
    public $encoding;

    /**
     * @var string
     */
    public $handle = 'json';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if ($this->encoding === null) {
            $this->encoding = Craft::$app->getResponse()->charset;
        }
    }

    /**
     * @inheritDoc
     */
    public static function type(): string
    {
        return Reports::t('JSON');
    }

    /**
     * @return string
     */
    public static function fileExtension(): string
    {
        return Response::FORMAT_JSON;
    }

    /**
     * @param Response $response
     */
    protected function modifyResponse(Response $response)
    {
        if (stripos($this->contentType, 'charset') === false) {
            $this->contentType .= '; charset=' . $this->encoding;
        }
        $response->getHeaders()->set('Content-Type', $this->contentType);

        $response->format = $response::FORMAT_RAW;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(SourceInterface $source): StreamInterface
    {
        return new JsonStream(
            new BufferJsonEncoder($source->getData())
        );
    }
}
