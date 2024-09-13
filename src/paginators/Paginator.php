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
    protected $pageSize = 10;

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
    protected $isQueryCount = false;

    /**
     * 当前页码参数名称
     * @var string
     */
    protected $pageVar = 'page';

    /**
     * 每页大小参数名称
     * @var string
     */
    protected $pageSizeVar = 'psize';

    /**
     * 分页样式类路径
     * @var string
     */
    protected $styleClass = PaginatorStyle::class;

    public function __construct(int $currentPage, int $pageSize = 10)
    {
        $this->setCurrentPage($currentPage);
        $this->setPageSize($pageSize);
    }

    /**
     * 设置总条数
     *<B>说明：</B>
     *<pre>
     * 略
     *</pre>
     * @param int $totalCount
     * @return void
     */
    public function setTotalCount(int $totalCount = 0):void
    {
        $this->totalCount = $totalCount;
    }

    public function getTotalCount():int
    {
        return $this->totalCount;
    }

    public function setCurrentPage(int $currentPage):void
    {
        $this->currentPage = $currentPage;
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

    public function setPageSize(int $pageSize):void
    {
        $this->pageSize = $pageSize;
    }

    public function getPageVar():string
    {
        return $this->pageVar;
    }

    public function setPageVar(string $pageVar):void
    {
        $this->pageVar = $pageVar;
    }

    public function getPageSizeVar():string
    {
        return $this->pageSizeVar;
    }

    public function setPageSizeVar(string $pageSizeVar):void
    {
        $this->pageSizeVar = $pageSizeVar;
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
        if ($this->pageSize <= 0) {
            return 10;
        }

        return $this->pageSize;
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

    public function asQueryCount(bool $isQueryCount = true):void
    {
        $this->isQueryCount = $isQueryCount;
    }

    public function getQueryCountStatus():bool
    {
        return $this->isQueryCount;
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

    public function setData(?array $data = []):void
    {
        $this->data = $data;
    }

    public function isEmpty():bool
    {
        return empty($this->data);
    }

    public function toArray():array
    {
        return [
            'pageSize'=>$this->getPageSize(),
            'totalPage'=>$this->getTotalPage(),
            'totalCount'=>$this->getTotalCount(),
            'currentPage'=>$this->getCurrentPage(),
            'data'=>$this->data,
        ];
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
