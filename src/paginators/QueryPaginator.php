<?php
namespace hehe\core\hpage\paginators;

/**
 * 页面参数分页工具
 *<B>说明：</B>
 *<pre>
 *  略
 *</pre>
 *
 */
class QueryPaginator extends Paginator
{
    /**
     * 当前页码
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @var int
     */
    protected $currentPage = null;

    /**
     * 每页大小
     *<B>说明：</B>
     *<pre>
     *  略
     *</pre>
     * @var int
     */
    protected $pageSize = null;

    /**
     * query 最后id
     * @var string
     */
    protected $queryLastVar = 'lastId';

    /**
     * 是否启用最后id模式
     * @var bool
     */
    protected $lastMode = false;

    /**
     * 最后id列名
     * @var string
     */
    protected $dataLastVar = 'id';

    /**
     * 页面参数
     * @var array
     */
    protected $query = [];

    public function __construct(array $query = [], ?int $pageSize = null)
    {
        $this->setQuery($query);
        $this->setPageSize($pageSize);
    }

    public function setQuery(array $query):self
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery():array
    {
        return $this->query;
    }

    public function asLastMode(string $dataLastVar = 'id',string $queryLastVar = 'lastId'):self
    {
        $this->lastMode = true;
        $this->dataLastVar = $dataLastVar;
        $this->queryLastVar = $queryLastVar;

        return $this;
    }

    public function getDataLastVar():string
    {
        return $this->dataLastVar;
    }

    public function getQueryLastVar():string
    {
        return $this->queryLastVar;
    }

    public function isLastModeStatus():bool
    {
        return $this->lastMode;
    }

    /**
     * @return bool
     */
    public function getQueryCountStatus():bool
    {
        if ($this->isQueryCount === true) {
            return true;
        }

        if ($this->lastMode === true) {
            if (!empty($this->query[$this->queryLastVar])) {
                // 非第一次分页,无需查询总条数
                return false;
            } else {
                // 第一次查询,lastId 为空,需要查询总条数
                return true;
            }
        }

        return true;
    }

    public function getDataLastId()
    {
        $idVal = null;
        if ($this->lastMode === true && !empty($this->data)) {
            $lastIdIndex = count($this->data) - 1;
            $idVal = $this->data[$lastIdIndex][$this->dataLastVar];
        }

        return $idVal;
    }

    public function getQueryLastId()
    {
        $idVal = null;
        if ($this->lastMode === true && isset($this->query[$this->queryLastVar])) {
            $idVal = $this->query[$this->queryLastVar];
        }

        return $idVal;
    }

    public function getCurrentPage():int
    {
        $pageNum = 0;
        if (!is_null($this->currentPage)) {
            $pageNum = $this->currentPage;
        } else if (isset($this->query[$this->pageVar])) {
            $pageNum = intval($this->query[$this->pageVar]);
        }

        if ($pageNum <= 0) {
            $pageNum = 1;
        }

        return $pageNum;
    }

    public function getPageSize():int
    {
        $pageSize = 0;
        if (!is_null($this->pageSize)) {
            $pageSize = $this->pageSize;
        } else if (isset($this->query[$this->pageSizeVar])) {
            $pageSize = intval($this->query[$this->pageSizeVar]);
        }

        if ($pageSize <= 0) {
            $pageSize = 10;
        }

        return $pageSize;
    }

    public function toArray():array
    {
        $page = parent::toArray();
        $page['lastId'] = $this->getDataLastId();
        $page['queryLastVar'] = $this->getQueryLastVar();
        $page['pageVar'] = $this->getPageVar();
        $page['pageSizeVar'] = $this->getPageSizeVar();

        return $page;
    }

}
