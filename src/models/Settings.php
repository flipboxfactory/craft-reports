<?php

namespace flipbox\craft\reports\models;

use craft\base\Model;
use flipbox\craft\reports\formats\CSV;
use flipbox\craft\reports\formats\FormatInterface;
use flipbox\craft\reports\Reports;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Settings extends Model
{
    /**
     * @var array|null
     */
    private $defaultFormat = [
        'class' => CSV::class
    ];

    /**
     * @return FormatInterface
     * @throws \yii\base\InvalidConfigException
     */
    public function getDefaultFormat(): FormatInterface
    {
        return Reports::getInstance()->getFormats()->create(
            $this->defaultFormat
        );
    }
}
