<?php

namespace flipbox\craft\reports\sources\filters;

interface FilterInterface
{
    /**
     * @param array $config
     * @return void
     */
    public function configure(array $config);
}
