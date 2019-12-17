<?php

namespace flipbox\craft\reports\sources;

interface SourceInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config);

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @return array
     */
    public function getColumns(): array;

    /**
     * @return array
     */
    public function getData(): array;
}
