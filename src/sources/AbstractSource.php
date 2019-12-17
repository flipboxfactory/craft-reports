<?php

namespace flipbox\craft\reports\sources;

use flipbox\craft\ember\helpers\ObjectHelper;
use yii\base\BaseObject;

abstract class AbstractSource extends BaseObject implements SourceInterface
{
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
     * @return array
     */
    public function getParams(): array
    {
        return [];
    }
}
