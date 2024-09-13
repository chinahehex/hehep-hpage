<?php
namespace hehe\core\hpage\styles;

/**
 * 分页工具
 *<B>说明：</B>
 *<pre>
 *  略
 *</pre>
 */
class NumPaginatorStyle extends PaginatorStyle
{
    /**
     * 最大数字页
     * @var int
     */
    protected $numPageMax = 5;

    public function __construct(int $numPageMax = 5)
    {
        $this->numPageMax = $numPageMax;
    }

    /**
     * @param int $numPageMax
     */
    public function setNumPageMax(int $numPageMax): void
    {
        $this->numPageMax = $numPageMax;
    }

    public function getNumPageMax():int
    {
        return $this->numPageMax;
    }

    public function getNumPage():array
    {
        $cur_page = $this->paginator->getCurrentPage();
        $total_page = $this->paginator->getTotalPage();

        $beginPage = max(1,$cur_page - intval($this->numPageMax/2));
        $endPage = $beginPage + $this->numPageMax;

        if ($endPage >= $total_page) {
            $endPage = $total_page;
            $beginPage = max(1,$endPage - $this->numPageMax + 1);
        }

        $numpageList = [];

        for ($num = $beginPage;$num <= $endPage;$num++) {
            $numpageList[$num] = $this->buildUrl($num);
        }

        return $numpageList;
    }


    public function toArray():array
    {
        $paginatorArr = $this->paginator->toArray();
        $styleArr = parent::toArray();
        $styleArr['numPageUrl'] = $this->getNumPage();
        $styleArr['numPageMax'] = $this->getNumPageMax();
        $paginatorArr['style'] = $styleArr;

        return $paginatorArr;
    }


}
