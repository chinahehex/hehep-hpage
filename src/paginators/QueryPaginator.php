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
     * 最后id排序方式
     * @var string
     */
    protected $lastSort = 'desc';

    /**
     * 页面参数
     * @var array
     */
    protected $query = [];

    protected $retAlias = [
        'total'=>'total',
        'psize'=>'psize',
        'totalPage'=>'totalPage',
        'currentPage'=>'currentPage',
        'lastId'=>'lastId'
    ];

    public function __construct($query = [], ?int $pageSize = null)
    {
        $this->setQuery($query);
        $this->setPageSize($pageSize);
    }

    public function setQuery($query):self
    {
        $this->query = $query;

        return $this;
    }

    /**
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function asLastMode(string $dataLastVar = 'id',string $lastSort = 'desc',string $queryLastVar = 'lastId'):self
    {
        $this->lastMode = true;
        $this->dataLastVar = $dataLastVar;
        $this->lastSort = $lastSort;
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

    public function getLastSort():string
    {
        return $this->lastSort;
    }

    public function isLast():bool
    {
        return $this->lastMode;
    }

    /**
     * @return bool
     */
    public function isQueryCount():bool
    {
        if ($this->queryCount === true) {
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

    public function setQueryLastId($idVal):self
    {
        $this->query[$this->queryLastVar] = $idVal;

        return $this;
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
        $psize = 0;
        if (!is_null($this->psize)) {
            $psize = $this->psize;
        } else if (isset($this->query[$this->psizeVar])) {
            $psize = intval($this->query[$this->psizeVar]);
        }

        if ($psize <= 0) {
            $psize = 10;
        }

        return $psize;
    }

    public function toArray(array $retAlias = []):array
    {
        $page = [
            'total'=>$this->getTotalCount(),
            'psize'=>$this->getPageSize(),
            'totalPage'=>$this->getTotalPage(),
            'currentPage'=>$this->getCurrentPage(),
            'data'=>$this->data,
            'pageVar'=>$this->getPageVar(),
            'psizeVar'=>$this->getPageSizeVar(),
            'lastId'=>$this->getDataLastId(),
        ];

        if (!$this->isLast()) {
            unset($page['lastId']);
        }

        $params = [];

        if (empty($retAlias)) {
            $retAlias = $this->retAlias;
        }

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

}
