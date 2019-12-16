<?php

namespace flipbox\craft\reports\cp\controllers\view;

use craft\helpers\UrlHelper;
use flipbox\craft\reports\Reports;
use flipbox\craft\reports\reports\ReportInterface;
use yii\web\Response;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class ReportsController extends AbstractController
{
    /**
     * The template base path
     */
    const TEMPLATE_BASE = parent::TEMPLATE_BASE . '/reports';

    /**
     * The index view template path
     */
    const TEMPLATE_INDEX = self::TEMPLATE_BASE . '/index';

    /**
     * The report view template path
     */
    const TEMPLATE_VIEW = self::TEMPLATE_BASE . '/view';

    /**
     * @return Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex(): Response
    {
        $variables = [];
        $this->baseVariables($variables);

        // All the configured reports
        $variables['reports'] = Reports::getInstance()->getReports()->all();

        return $this->renderTemplate(
            static::TEMPLATE_INDEX,
            $variables
        );
    }

    /**
     * @param string $identifier
     * @return Response
     * @throws \yii\base\Exception
     */
    public function actionView(string $identifier): Response
    {
        $report = Reports::getInstance()->getReports()->get($identifier);

        $variables = [];
        $this->upsertVariables($report, $variables);

        // All the configured reports
        $variables['report'] = $report;

        return $this->renderTemplate(
            static::TEMPLATE_VIEW,
            $variables
        );
    }


    /*******************************************
     * VARIABLES
     *******************************************/

    /**
     * @inheritdoc
     */
    protected function upsertVariables(ReportInterface $report, array &$variables = [])
    {
        $this->baseVariables($variables);

        $variables['title'] .= ': ' . $report->getName();

        // Breadcrumbs
        $variables['crumbs'][] = [
            'label' => $report->getName(),
            'url' => UrlHelper::url($this->getBaseCpPath())
        ];
    }
}
