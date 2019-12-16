<?php

namespace flipbox\craft\reports\reports;

use yii\web\Response;

interface ReportInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getHandle(): string;

    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config);

    /**
     * @return string|null
     */
    public function getCpDashboardHtml();

    /**
     * @return string|null
     */
    public function getCpActionHtml();

    /**
     * @return string|null
     */
    public function getDashboardHtml();

    /**
     * The Url that runs the report.
     *
     * @param array $params
     * @return string
     */
    public function getUrl(array $params = []): string;

    /**
     * @return Response
     */
    public function run(): Response;
}
