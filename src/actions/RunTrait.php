<?php

namespace flipbox\craft\reports\actions;

use Craft;
use craft\helpers\ArrayHelper;
use flipbox\craft\ember\actions\CheckAccessTrait;
use flipbox\craft\reports\events\RunReport;
use flipbox\craft\reports\Reports;
use flipbox\craft\reports\reports\ReportInterface;
use yii\web\ForbiddenHttpException;

trait RunTrait
{
    use CheckAccessTrait;

    /**
     * @param ReportInterface $report
     * @param bool|null $canRun
     * @return mixed|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    protected function runInternal(ReportInterface $report, bool $canRun = null)
    {
        // Report access
        if (($access = $this->checkReportAccess($report, $canRun)) !== true) {
            return $access;
        }

        return $report->run();
    }

    /**
     * @param ReportInterface $report
     * @param bool|null $canRun
     * @return bool
     * @throws ForbiddenHttpException
     */
    protected function checkReportAccess(ReportInterface $report, bool $canRun = null): bool
    {
        // Check access
        if (($access = $this->checkAccess($report)) !== true) {
            return $access;
        }

        // Trigger event (to check if report can be run)
        $event = new RunReport([
            'report' => $report,
            'canRun' => $canRun
        ]);

        $event->trigger(
            get_class($report),
            $event::EVENT_NAME,
            $event
        );

        if (!$event->canRun) {
            throw new ForbiddenHttpException(
                Reports::t(
                    $event->forbiddenMessage
                )
            );
        }

        return true;
    }

    /**
     * @return array
     */
    protected function getParams(): array
    {
        $params = Craft::$app->getRequest()->getQueryParams();
        ArrayHelper::remove($params, Craft::$app->getConfig()->getGeneral()->pathParam);

        return $params;
    }
}
