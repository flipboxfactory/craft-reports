<?php

namespace flipbox\craft\reports\actions;

use Craft;
use craft\helpers\ArrayHelper;
use flipbox\craft\ember\actions\CheckAccessTrait;
use flipbox\craft\reports\events\RunReport;
use flipbox\craft\reports\Reports;
use flipbox\craft\reports\reports\ReportInterface;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Run extends Action
{
    use CheckAccessTrait;

    /**
     * @param string|null $identifier
     * @param bool|null $canRun
     * @return mixed|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run(string $identifier = null, bool $canRun = null)
    {
        // Retrieve report
        $report = Reports::getInstance()->getReports()->get(
            $this->reportIdentifier($identifier)
        );

        // Configure w/ additional settings / params
        $report->configure($this->getParams());

        return $this->runInternal($report, $canRun);
    }

    /**
     * @param ReportInterface $report
     * @param bool|null $canRun
     * @return mixed|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    protected function runInternal(ReportInterface $report, bool $canRun = null)
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

        return $report->run();
    }

    /**
     * @return array
     */
    private function getParams(): array
    {
        $params = Craft::$app->getRequest()->getQueryParams();
        ArrayHelper::remove($params, 'identifier');
        ArrayHelper::remove($params, Craft::$app->getConfig()->getGeneral()->pathParam);

        return $params;
    }

    /**
     * @param null $identifier
     * @return string
     * @throws NotFoundHttpException
     */
    private function reportIdentifier($identifier = null): string
    {
        if (!empty($identifier)) {
            return $identifier;
        }

        $identifier = Craft::$app->getRequest()->getParam(
            'identifier',
            Craft::$app->getRequest()->getParam(
                'key',
                Craft::$app->getRequest()->getParam('handle')
            )
        );

        if (empty($identifier)) {
            throw new NotFoundHttpException("Invalid report identifier.");
        }

        return $identifier;
    }
}
