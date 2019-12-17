<?php

namespace flipbox\craft\reports\sources;

use flipbox\craft\reports\sources\filters\FilterTrait;
use yii\db\QueryInterface;

abstract class AbstractQuerySource extends AbstractSource
{
    use FilterTrait;

    const LIMIT = null;

    /**
     * @var int
     */
    public $limit = self::LIMIT;

    /**
     * @var callable
     */
    public static $transformer = [
        self::class,
        'transform'
    ];

    /**
     * @var array
     */
    protected static $columns = [];

    /**
     * @return QueryInterface
     */
    abstract protected function getQuery(): QueryInterface;

    /**
     * @return array
     */
    public function getParams(): array
    {
        $params = [
            'filter' => $this->getFilter()->getParams() ?? null
        ];

        if ($this->limit != self::LIMIT) {
            $params['limit'] = $this->limit;
        }

        return $params;
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return static::$columns;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        $query = $this->getQuery()
            ->limit($this->limit);

        return $this->prepData(
            $query->all()
        );
    }

    /**
     * @inheritDoc
     */
    protected function prepData(array $data): array
    {
        return call_user_func(
            static::$transformer,
            $data
        );
    }

    /**
     * @param $data
     * @return array
     */
    public static function transform(array $data): array
    {
        $results = [];

        foreach ($data as $item) {
            $results[] = array_intersect_key(
                $item,
                static::$columns
            );
        }

        return $results;
    }
}
