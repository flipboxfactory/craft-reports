<?php

namespace flipbox\craft\reports\actions;

use flipbox\craft\reports\Reports;
use yii\base\Action;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Run extends Action
{
    use RunTrait;

    /**
     * @param string|null $identifier
     * @param bool|null $canRun
     * @return mixed|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run(string $identifier, bool $canRun = null)
    {
        // Retrieve report
        $report = Reports::getInstance()->getReports()->get(
            $identifier
        );

        // Configure w/ additional settings / params
        $report->configure($this->getParams());

        return $this->runInternal($report, $canRun);
    }
}
