<?php

namespace flipbox\craft\reports\reports;

use flipbox\craft\ember\helpers\ObjectHelper;
use flipbox\craft\reports\sources\SourceInterface;

/**
 * @property-write string $sourceClass
 */
trait SourceTrait
{
    /**
     * @var SourceInterface
     */
    private $source;

    /**
     * @param array $config
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function configureSource(array $config = [])
    {
        $this->getSource()->configure($config);
    }

    /**
     * @return SourceInterface
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function getSource(): SourceInterface
    {
        if (null === $this->source) {
            if (null !== ($class = $this->sourceClass ?? null)) {
                $this->source = ObjectHelper::create(['class' => $class], SourceInterface::class);
            }
        }

        return $this->source;
    }

    /**
     * @param SourceInterface $source
     * @return $this
     */
    public function setSource(SourceInterface $source)
    {
        $this->source = $source;
        return $this;
    }
}
