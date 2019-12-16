<?php

namespace flipbox\craft\reports\formats;

use Craft;
use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\reports\sources\SourceInterface;
use Psr\Http\Message\StreamInterface;
use yii\base\BaseObject;
use yii\web\Response;

abstract class AbstractFormat extends BaseObject implements FormatInterface
{
//    /**
//     * The name (human readable)
//     *
//     * @var string
//     */
//    public $name;
//
    /**
     * The handle (reference)
     *
     * @var string
     */
    public $handle;
//
//    /**
//     * @return string
//     */
//    public function getName(): string
//    {
//        return $this->name;
//    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @inheritDoc
     */
    public function configure(array $config)
    {
        ObjectHelper::populate(
            $this,
            $config
        );
    }

    /**
     * @inheritDoc
     */
    public function run(SourceInterface $source): StreamInterface
    {
        // Modify response
        $this->modifyResponse(Craft::$app->getResponse());

        // Structure data
        return $this->prepareData($source);
    }

    /**
     * @param Response $response
     */
    protected function modifyResponse(Response $response)
    {
        $response->format = $response::FORMAT_RAW;
    }

    /**
     * @param SourceInterface $source
     * @return mixed
     */
    abstract protected function prepareData(SourceInterface $source): StreamInterface;
}
