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
     * 当前页码参数名称
     * @var string
     */
    protected $pageVar = 'page';

    /**
     * 每页大小参数名称
     * @var string
     */
    protected $psizeVar = 'psize';

    /**
     * query最后id
     * @var string
     */
    protected $lastIdVar = 'lastId';

    // 返回的变量别名
    protected $retAlias = [
        'total'=>'total',
        'psize'=>'psize',
        'totalPage'=>'totalPage',
        'currentPage'=>'currentPage',
        'data'=>'data',
        'lastId'=>'lastId'
    ];

    // url地址生成器
    protected $uriBuilder;

    /**
     * 分页器集合
     * @var array
     */
    protected $paginators = [
        'paginator'=>Paginator::class,
        'query'=>QueryPaginator::class
    ];

    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setConfig($config);
        }
    }

    public function setConfig(array $config = []): void
    {
        foreach ($config as $name=>$value) {
            if ($name === 'paginators') {
                $this->paginators = array_merge($this->paginators,$value);
            } else {
                $this->{$name} = $value;
            }
        }
    }

    /**
     * 设置构建URL函数
     * @param string|callable|array|null $uriBuilder
     */
    public function setUriBuilder($uriBuilder):self
    {
        if (is_string($uriBuilder)) {
            $this->uriBuilder = explode('::',  $uriBuilder);
        } else {
            $this->uriBuilder = $uriBuilder;
        }
        
        return $this;
    }

    /**
     * 获取构建URL函数
     * @return string|callable|array|null
     */
    public function getUriBuilder()
    {
        return $this->uriBuilder;
    }

    public function setPaginator(string $alias, string $paginator,array $config = []):void
    {
        $this->paginators[$alias] = ['class'=>$paginator,'config'=>$config];
    }

    public function createPaginator(string $name = '', ...$args):Paginator
    {
        $options = [];
        $class = '';
        if (strpos($name, '\\') !== false) {
            $class = $name;
        } else {
            if (isset($this->paginators[$name])) {
                $paginatorConfig = $this->paginators[$name];
                if (is_array($paginatorConfig)) {
                    $class = $paginatorConfig['class'];
                    if (isset($paginatorConfig['config'])) {
                        $options = $paginatorConfig['config'];
                    }
                } else {
                    $class = $paginatorConfig;
                }
            } else {
                $class = __NAMESPACE__ . '\\paginators\\' . ucfirst($name) . 'Paginator';
            }
        }

        /** @var Paginator $page */
        $page = new $class(...$args);

        $page->setConfig(array_merge([
            'pageVar'=>$this->pageVar,
            'psizeVar'=>$this->psizeVar,
            'lastIdVar'=>$this->lastIdVar,
            'retAlias'=>$this->retAlias,
            'pagination'=>$this,
        ],$options));

        return $page;
    }

    public function __call($method, $params)
    {
        if (ucfirst(substr($method,-9)) === 'Paginator') {
            return $this->createPaginator(substr($method,0,-9), ...$params);
        } else {
            return $this->createPaginator($method, ...$params);
        }
    }

}
