<?php

namespace flipbox\craft\reports\reports;

use Craft;
use craft\helpers\UrlHelper;
use craft\helpers\ArrayHelper;
use yii\web\Response;

class QueryReport extends AbstractReport
{
    use SourceTrait,
        FormatTrait;

    const CP_DASHBOARD_TEMPLATE_PATH = 'reports/types/query/dashboard';
    const CP_ACTIONS_TEMPLATE_PATH = 'reports/types/query/actions';

    /**
     * Identify whether this report should be output to screen or file (download).
     *
     * @var bool
     */
    public $toFile = false;

    /**
     * The file name
     */
    public $fileName = '{{ report.name ?? "report" }}';

    /**
     * @var string|null
     */
    public $sourceClass;

    /**
     * @inheritDoc
     *
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function configure(array $config)
    {
        if (null !== ($source = ArrayHelper::remove($config, 'source'))) {
            $this->configureSource($source);
        }

        if (null !== ($format = ArrayHelper::remove($config, 'format'))) {
            if (is_string($format)) {
                $format = ['type' => $format];
            }

            $this->configureFormat($format);
        }

        parent::configure($config);
    }

    /**
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\HttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     */
    public function run(): Response
    {
        $response = Craft::$app->getResponse();

        $result = $this->getFormat()->run(
            $this->getSource()
        );

        if ($this->toFile) {
            return $response->sendContentAsFile(
                $result,
                $this->getFileName()
            );
        }

        $response->content = $result;

        return $response;
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFileName()
    {
        return Craft::$app->getView()->renderString(
            $this->fileName,
            [
                    'report' => $this
                ]
        ) . '.' . $this->getFormat()::fileExtension();
    }

    /**
     * @return string|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function getCpActionHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            static::CP_ACTIONS_TEMPLATE_PATH,
            [
                'downloads' => [
                    'CSV' => $this->getUrl([
                        'toFile' => true,
                        'format' => 'csv'
                    ]),
                    'JSON' => $this->getUrl([
                        'toFile' => true,
                        'format' => 'json'
                    ])
                ],
            ]
        );
    }

    /**
     * @return string|null
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function getCpDashboardHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            static::CP_DASHBOARD_TEMPLATE_PATH,
            [
                'columns' => $this->getSource()->getColumns(),
                'dataUrl' => UrlHelper::siteUrl(
                    $this->getUrl()
                ),
                'params' => [
                    'format' => 'json',
                    'toFile' => true,
                    'source' => $this->getSource()->getParams()
                ]
            ]
        );
    }
}
