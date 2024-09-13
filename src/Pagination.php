<?php
namespace hehe\core\hpage;

use hehe\core\hpage\paginators\Paginator;
use hehe\core\hpage\paginators\QueryPaginator;
use hehe\core\hpage\styles\PaginatorStyle;

/**
 * 分页工具
 *<B>说明：</B>
 *<pre>
 *  Paginator
 *</pre>
 * @method static QueryPaginator queryPaginator(array $query = [], ?int $pageSize = null)
 * @method static Paginator paginator(int $currentPage, int $pageSize = 10)
 */
class Pagination
{
    /**
     * 分页器集合
     * @var array
     */
    protected static $paginators = [
        'paginator'=>Paginator::class,
        'query'=>QueryPaginator::class
    ];

    /**
     * 设置构建URL函数
     * @param callable|null $uriBuilder
     */
    public static function setUriBuilder(?callable $uriBuilder = null):void
    {
        PaginatorStyle::setUriBuilder($uriBuilder);
    }

    public static function setPaginator(string $alias, string $paginator,...$args):void
    {
        static::$paginators[$alias] = ['class'=>$paginator,'args'=>$args];
    }

    public static function createPaginator(string $name = '', ...$args):Paginator
    {
        $class = '';
        if (strpos($name, '\\') !== false) {
            $class = $name;
        } else {
            if (isset(static::$paginators[$name])) {
                $config = static::$paginators[$name];
                if (is_array($config)) {
                    $class = $config['class'];
                    if (isset($config['args'])) {
                        $args = $args + $config['args'];
                    }
                } else {
                    $class = $config;
                }
            } else {
                $class = __NAMESPACE__ . '\\paginators\\' . ucfirst($name) . 'Paginator';
            }
        }

        return new $class(...$args);
    }

    public static function __callStatic($method, $params)
    {
        if (ucfirst(substr($method,-9)) === 'Paginator') {
            return static::createPaginator(substr($method,0,-9), ...$params);
        } else {
            return static::createPaginator($method, ...$params);
        }
    }

}
