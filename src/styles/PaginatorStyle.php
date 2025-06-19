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
     * @var array|string|callable
     */
    protected $uriBuilder;

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

    protected $uri = null;

    protected $uriParams = [];

    public function __construct()
    {

    }

    public function setPaginator(Paginator $paginator):void
    {
        $this->paginator = $paginator;
    }

    /**
     * 设置构建URL函数
     * @param string|array|callable $uriBuilder
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

    public function setPath(string $path):self
    {
        $this->path = $path;
        $this->parseUrlArr = parse_url($this->path);

        return $this;
    }

    public function setPageVar(string $pageVar):self
    {
        $this->pageVar = $pageVar;

        return $this;
    }

    public function getPageVar():string
    {
        return $this->pageVar;
    }

    public function buildUrl(int $pageNum):string
    {
        if (!is_null($this->uriBuilder) && !is_null($this->uri)) {
            $uri_params = array_merge($this->uriParams, [$this->pageVar=>$pageNum]);
            return call_user_func($this->uriBuilder, $this->uri,$uri_params);
        } else if ($this->path !== '') {
            if (strpos($this->path,'[PAGE]') !== false) {
                return str_replace('[PAGE]', (string) $pageNum, $this->path);
            } else {
                return $this->buildPathUrl($pageNum);
            }
        } else {
            return '';
        }
    }

    public function setUrl(string $url = '',array $params = []):self
    {
        $this->uri = $url;
        $this->uriParams = $params;

        return $this;
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
            parse_str($query,$queryArr);
            $queryArr[$this->pageVar] = $pageNum;
            $url .= '?' . http_build_query($queryArr);
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
