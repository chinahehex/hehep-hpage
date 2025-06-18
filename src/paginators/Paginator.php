<?php
namespace hehe\core\hpage\paginators;

use hehe\core\hpage\styles\PaginatorStyle;

/**
 * 分页工具
 *<B>说明：</B>
 *<pre>
 *  略
 *</pre>
 */
class Paginator
{
    /**
     * 总条数
     * @var int
     */
    protected $totalCount = 0;

    /**
     * 每页大小
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @var int
     */
    protected $psize = 10;

    /**
     * 当前页码
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @var int
     */
    protected $currentPage = 1;

    /**
     * 分页数据
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @var array
     */
    public $data = null;

    /**
     * 是否查询总条数
     * @var bool
     */
    protected $queryCount = false;

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

    protected $retAlias = [
        'total'=>'total',
        'psize'=>'psize',
        'totalPage'=>'totalPage',
        'currentPage'=>'currentPage',
        'data'=>'data',
    ];

    /**
     * 分页样式类路径
     * @var string
     */
    protected $styleClass = PaginatorStyle::class;

    public function __construct(int $currentPage, int $psize = 10)
    {
        $this->setCurrentPage($currentPage);
        $this->setPageSize($psize);
    }

    public function setOptions(array $options = []): void
    {
        foreach ($options as $name=>$value) {
            if (property_exists($this,$name)) {
                $this->{$name} = $value;
            }
        }
    }

    /**
     * 设置总条数
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @param int $totalCount
     * @return self
     */
    public function setTotalCount(int $totalCount = 0):self
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    public function getTotalCount():int
    {
        return $this->totalCount;
    }

    public function setCurrentPage(?int $currentPage):self
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function setStyleClass(string $styleClass):self
    {
        $this->styleClass = $styleClass;

        return $this;
    }

    public function getStyleClass():string
    {
        return $this->styleClass;
    }

    public function bindStyle(string $styleClass):self
    {
        $this->styleClass = $styleClass;

        return $this;
    }

    public function setPageSize(?int $psize):self
    {
        $this->psize = $psize;

        return $this;
    }

    public function getPageVar():string
    {
        return $this->pageVar;
    }

    public function setPageVar(string $pageVar):self
    {
        $this->pageVar = $pageVar;

        return $this;
    }

    public function getPageSizeVar():string
    {
        return $this->psizeVar;
    }

    public function setPageSizeVar(string $psizeVar):self
    {
        $this->psizeVar = $psizeVar;

        return $this;
    }

    /**
     * 返回分页大小
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @return int
     */
    public function getPageSize():int
    {
        if ($this->psize <= 0) {
            return 10;
        }

        return $this->psize;
    }

    /**
     * 返回当前页码
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @return int
     */
    public function getCurrentPage():int
    {
        if ($this->currentPage <= 0) {
            return 1;
        }

        return $this->currentPage;
    }

    public function asQueryCount(bool $queryCount = true):self
    {
        $this->queryCount = $queryCount;

        return $this;
    }

    public function isQueryCount():bool
    {
        return $this->queryCount;
    }

    public function getTotalPage():int
    {
        return ceil($this->totalCount/$this->getPageSize());
    }

    public function getLimit():int
    {
        return $this->getPageSize();
    }

    public function getOffset():int
    {
        // 读取当前页面
        $pageNum = $this->getCurrentPage();
        if ($pageNum <= 1) {
            return 0;
        } else {
            return ($pageNum - 1) * $this->getPageSize();
        }
    }

    public function setData(?array $data = []):self
    {
        $this->data = $data;

        return $this;
    }

    public function getData():?array
    {
        return $this->data;
    }

    public function isEmpty():bool
    {
        return empty($this->data);
    }

    public function toArray(array $retAlias = []):array
    {
        $page = [
            'total'=>$this->getTotalCount(),
            'psize'=>$this->getPageSize(),
            'totalPage'=>$this->getTotalPage(),
            'currentPage'=>$this->getCurrentPage(),
            'data'=>$this->data,
        ];

        if (empty($retAlias)) {
            $retAlias = $this->retAlias;
        }
        $params = [];
        foreach ($retAlias as $name=>$alias) {
            if (is_numeric($name)) {
                $name = $alias;
            }
            if (isset($page[$name])) {
                $params[$alias] = $page[$name];
            }
        }

        return $params;
    }

    public function newStyle(...$args):PaginatorStyle
    {
        /** @var PaginatorStyle $pstyle */
        $paginatorStyle = $this->styleClass;
        $pstyle = new $paginatorStyle(...$args);
        $pstyle->setPageVar($this->getPageVar());
        $pstyle->setPaginator($this);

        return $pstyle;
    }
}
