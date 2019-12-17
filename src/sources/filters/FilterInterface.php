<?php

namespace flipbox\craft\reports\sources\filters;

interface FilterInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config);

    /**
     * Values that have been set and used to filter the source.  Useful when you need to reference or pass
     * them around.
     *
     * @return array
     */
    public function getParams(): array;
}
