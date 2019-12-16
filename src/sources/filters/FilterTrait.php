<?php

namespace flipbox\craft\reports\sources\filters;

/**
 * @property-write string $filterClass
 */
trait FilterTrait
{
    /**
     * @var FilterInterface|null
     */
    private $filter;

    /**
     * @return FilterInterface|null
     */
    public function getFilter()
    {
        if (null === $this->filter) {
            if (null !== ($class = $this->filterClass ?? null)) {
                $this->filter = new $class;
            }
        }

        return $this->filter;
    }

    /**
     * Populate filter (if applicable)
     *
     * @param array $config
     * @return $this
     */
    public function setFilter(array $config = [])
    {
        if (null !== ($filter = $this->getFilter())) {
            $filter->configure($config);
        }

        return $this;
    }
}
