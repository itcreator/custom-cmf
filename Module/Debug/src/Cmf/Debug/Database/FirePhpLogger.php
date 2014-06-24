<?php
/**
 * This file is part of the Custom CMF.
 *
 * @link      https://github.com/itcreator/custom-cmf for the canonical source repository
 * @copyright Copyright (c) 2011 Vital Leshchyk <vitalleshchyk@gmail.com>
 * @license   https://github.com/itcreator/custom-cmf/blob/master/LICENSE
 */
namespace Cmf\Debug\Database;

/**
 * FirePHP SQL logger for Doctrine 2
 *
 * @author Vital Leshchyk vitalleshchyk@gmail.com
 */
class FirePhpLogger implements \Doctrine\DBAL\Logging\SQLLogger
{
    /**
     * @var int
     */
    protected $microTime;

    /**
     * @var string
     */
    protected $sql;

    /**
     * @var array
     */
    protected $types;

    /**
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $queries;

    /**
     * @var array
     */
    protected $params;

    public function __construct()
    {
        $this->queries = [];
        $this->header = ['Time', 'Query', 'Parameters', 'Types'];
    }

    /**
     * Displaying
     */
    public function __destruct()
    {
        $time = 0;
        $n = count($this->queries);
        for ($i = 0; $i < $n; $i++) {
            $time += $this->queries[$i][0];
        }

        $table = array_merge([$this->header], $this->queries);
        \FB::table(sprintf('DB Queries (%d @ %f)', count($this->queries), number_format($time, 6)), $table);
    }

    /**
     * {@inheritdoc}
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->sql = $sql;
        $this->params = $params;
        $this->types = $types;
        $this->microTime = microtime(true);
    }

    /**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
        $this->queries[] = [
            number_format((\microtime(true) - $this->microTime) * 1000, 6) . 'ms',
            $this->sql,
            $this->params,
            $this->types,
        ];
    }
}
