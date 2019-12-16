<?php

namespace flipbox\craft\reports\sources\filters;

use flipbox\craft\ember\helpers\ObjectHelper;
use yii\base\BaseObject;

abstract class AbstractFilter extends BaseObject implements FilterInterface
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
}
