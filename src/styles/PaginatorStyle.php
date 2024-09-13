<?php
namespace hehe\core\hpage\styles;

use hehe\core\hpage\paginators\Paginator;

/**
 * 分页工具
 *<B>说明：</B>
 *<pre>
 *  略
 *</pre>
 */
class PaginatorStyle
{
    /**
     * 分页对象
     * @var Paginator
     */
    protected $paginator;

    /**
     * 构建URL函数
     * @var callable
     */
    protected static $uriBuilder;

    /**
     * url基础路径
     * @var string
     */
    protected $path = '';

    /**
     * 解析的URL信息
     * parse_url返回的url数组信息
     * @var array
     */
    protected $parseUrlArr = [];

    /**
     * 当前页码参数名称
     * @var string
     */
    protected $pageVar = 'psize';

    public function __construct()
    {

    }

    public function setPaginator(Paginator $paginator):void
    {
        $this->paginator = $paginator;
    }

    public static function setUriBuilder(?callable $uriBuilder = null):void
    {
        static::$uriBuilder = $uriBuilder;
    }

    public function setPath(string $path):void
    {
        $this->path = $path;
        $this->parseUrlArr = parse_url($this->path);
    }

    public function setPageVar(string $pageVar):void
    {
        $this->pageVar = $pageVar;
    }

    public function getPageVar():string
    {
        return $this->pageVar;
    }

    public function buildUrl(int $pageNum):string
    {
        if (!is_null(static::$uriBuilder)) {
            return call_user_func(static::$uriBuilder, [$this->pageVar=>$pageNum]);
        } else if ($this->path !== '') {
            return $this->buildPathUrl($pageNum);
        } else {
            return '';
        }
    }

    protected function buildPathUrl(int $pageNum):string
    {
        $url = '';
        if (isset($this->parseUrlArr['scheme'])) {
            $url .= $this->parseUrlArr['scheme'];
        }

        if (isset($this->parseUrlArr['host'])) {
            $url .= '://' . $this->parseUrlArr['host'];
        }

        if (isset($this->parseUrlArr['path'])) {
            $url .=  $this->parseUrlArr['path'];
        }

        if (isset($this->parseUrlArr['query'])) {
            $query = $this->parseUrlArr['query'];
            $url .= '?' . $query . '&' . $this->pageVar . '=' . $pageNum;
        } else {
            $url .= '?' . $this->pageVar . '=' . $pageNum;
        }

        if (isset($this->parseUrlArr['fragment'])) {
            $url .= '#' . $this->parseUrlArr['fragment'];
        }

        return $url;
    }

    /**
     * 获取当前页面地址
     */
    public function getCurrentPage():int
    {
        return $this->paginator->getCurrentPage();
    }

    public function getCurrentPageUrl():string
    {
        return $this->buildUrl($this->paginator->getCurrentPage());
    }

    public function getFirstPage():int
    {
        return 1;
    }

    public function getFirstPageUrl():string
    {
        return $this->buildUrl($this->getFirstPage());
    }

    public function getLastPage():int
    {
        return $this->paginator->getTotalPage();
    }

    public function getLastPageUrl():string
    {
        return $this->buildUrl($this->getLastPage());
    }

    public function getPrevPage():int
    {
        $pageNum = $this->paginator->getCurrentPage();
        if ($pageNum > 1) {
            return $pageNum - 1;
        } else {
            return $this->paginator->getTotalPage();
        }
    }

    public function getPrevPageUrl():string
    {
        return $this->buildUrl($this->getPrevPage());
    }

    public function getNextPage():int
    {
        $pageNum = $this->paginator->getCurrentPage();
        if ($pageNum < $this->paginator->getTotalPage()) {
            return $pageNum + 1;
        } else {
            return $this->paginator->getTotalPage();
        }
    }

    public function getNextPageUrl():string
    {
        return $this->buildUrl($this->getNextPage());
    }

    public function toArray():array
    {
        return [
            'currentPage'=>$this->getCurrentPage(),
            'currentPageUrl'=>$this->getCurrentPageUrl(),
            'firstPage'=>$this->getFirstPage(),
            'firstPageUrl'=>$this->getFirstPageUrl(),
            'lastPage'=>$this->getLastPage(),
            'lastPageUrl'=>$this->getLastPageUrl(),
            'prevPage'=>$this->getPrevPage(),
            'prevPageUrl'=>$this->getPrevPageUrl(),
            'nextPage'=>$this->getNextPage(),
            'nextPageUrl'=>$this->getNextPageUrl(),
            'pageVar'=>$this->getPageVar(),
        ] ;
    }


}
