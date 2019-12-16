<?php

namespace flipbox\craft\reports\reports;

use Craft;
use craft\helpers\UrlHelper;
use flipbox\craft\ember\helpers\ObjectHelper;
use yii\base\BaseObject;

abstract class AbstractReport extends BaseObject implements ReportInterface
{
    /**
     * The name (human readable)
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * The handle (reference)
     *
     * @var string
     */
    public $handle;

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param array $params
     * @return string
     * @throws \yii\base\Exception
     */
    public function getUrl(array $params = []): string
    {
        return UrlHelper::siteUrl(
            Craft::$app->getConfig()->getGeneral()->actionTrigger . '/reports/run/' . $this->getHandle(),
            $params
        );
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
    public function getDashboardHtml()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCpActionHtml()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getCpDashboardHtml()
    {
        return null;
    }
}
