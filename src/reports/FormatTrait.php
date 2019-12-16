<?php

namespace flipbox\craft\reports\reports;

use craft\helpers\ArrayHelper;
use flipbox\craft\reports\formats\FormatInterface;
use flipbox\craft\reports\Reports;

trait FormatTrait
{
    /**
     * @var FormatInterface
     */
    private $format;

    /**
     * @param array $config
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    protected function configureFormat(array $config = [])
    {
        if (null !== ($type = ArrayHelper::remove($config, 'type'))) {
            $this->setFormat(
                Reports::getInstance()->getFormats()->get($type)
            );
        }

        // Any prep work on the config prior to passing to source should be done here

        $this->getFormat()->configure($config);
    }

    /**
     * @param FormatInterface $format
     * @return $this
     */
    public function setFormat(FormatInterface $format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return FormatInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getFormat(): FormatInterface
    {
        if (null === $this->format) {
            $this->format = Reports::getInstance()->getSettings()->getDefaultFormat();
        }

        return $this->format;
    }
}
