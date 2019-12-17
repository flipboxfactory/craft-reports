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
     * Values that have been set and used to configure the source.  Useful when you need to reference or pass
     * them around.
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * An array of data attribute / column names.  In the format of ['name' => 'label]
     *
     * @return array
     */
    public function getColumns(): array;

    /**
     * Data that the source has produced
     *
     * @return array
     */
    public function getData(): array;
}
