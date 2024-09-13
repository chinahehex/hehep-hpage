<?php
namespace hpage\tests\units;
use hehe\core\hpage\styles\NumPaginatorStyle;
use hehe\core\hpage\paginators\Paginator;
use hehe\core\hpage\styles\PaginatorStyle;
use hehe\core\hpage\paginators\QueryPaginator;
use hpage\tests\TestCase;

class PaginatorStyleTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        // 设置生成
        PaginatorStyle::setUriBuilder(function (array $pageParams){
            return 'style?'.http_build_query($pageParams);
        });
    }

    // 单个测试之后(每个测试方法之后调用)
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testStyle()
    {
        $paginator = new QueryPaginator(['page'=>5]);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $this->assertTrue($pstyle->getCurrentPage() === 5);
        $this->assertTrue($pstyle->getFirstPage() === 1);
        $this->assertTrue($pstyle->getPrevPage() === 4);
        $this->assertTrue($pstyle->getNextPage() === 6);
        $this->assertTrue($pstyle->getLastPage() === 30);
    }

    public function testStyle1()
    {
        $paginator = new QueryPaginator(['page'=>31],10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPageVar('psize');
        $this->assertTrue($pstyle->getCurrentPage() === 31);
        $this->assertTrue($pstyle->getFirstPage() === 1);
        $this->assertTrue($pstyle->getPrevPage() === 30);
        $this->assertTrue($pstyle->getNextPage() === 30);
        $this->assertTrue($pstyle->getLastPage() === 30);
    }

    public function testStyle2()
    {
        $paginator = new Paginator(5);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $this->assertTrue($pstyle->getCurrentPage() === 5);
        $this->assertTrue($pstyle->getFirstPage() === 1);
        $this->assertTrue($pstyle->getPrevPage() === 4);
        $this->assertTrue($pstyle->getNextPage() === 6);
        $this->assertTrue($pstyle->getLastPage() === 30);
    }

    public function testStyle3()
    {
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPageVar('page');
        $this->assertTrue($pstyle->getCurrentPage() === 31);
        $this->assertTrue($pstyle->getFirstPage() === 1);
        $this->assertTrue($pstyle->getPrevPage() === 30);
        $this->assertTrue($pstyle->getNextPage() === 30);
        $this->assertTrue($pstyle->getLastPage() === 30);
    }

    public function testStyl4()
    {
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPageVar('pagex');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'style?pagex=31');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'style?pagex=1');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'style?pagex=30');
        $this->assertTrue($pstyle->getNextPageUrl() === 'style?pagex=30');
        $this->assertTrue($pstyle->getLastPageUrl() === 'style?pagex=30');
    }

    public function testStyl5()
    {
        PaginatorStyle::setUriBuilder(null);
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPath('style');
        $pstyle->setPageVar('psizex');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'style?psizex=31');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'style?psizex=1');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'style?psizex=30');
        $this->assertTrue($pstyle->getNextPageUrl() === 'style?psizex=30');
        $this->assertTrue($pstyle->getLastPageUrl() === 'style?psizex=30');
    }

    public function testStyl6()
    {
        PaginatorStyle::setUriBuilder(null);
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPath('http://www.baidu.com/user/style');
        $pstyle->setPageVar('psizex');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'http://www.baidu.com/user/style?psizex=31');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'http://www.baidu.com/user/style?psizex=1');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'http://www.baidu.com/user/style?psizex=30');
        $this->assertTrue($pstyle->getNextPageUrl() === 'http://www.baidu.com/user/style?psizex=30');
        $this->assertTrue($pstyle->getLastPageUrl() === 'http://www.baidu.com/user/style?psizex=30');
    }

    public function testStyl7()
    {
        PaginatorStyle::setUriBuilder(null);
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPath('http://www.baidu.com/user/style?a=1');
        $pstyle->setPageVar('psizex');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=31');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=1');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=30');
        $this->assertTrue($pstyle->getNextPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=30');
        $this->assertTrue($pstyle->getLastPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=30');
    }

    public function testStyl8()
    {
        PaginatorStyle::setUriBuilder(null);
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPath('http://www.baidu.com/user/style?a=1#abc');
        $pstyle->setPageVar('psizex');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=31#abc');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=1#abc');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=30#abc');
        $this->assertTrue($pstyle->getNextPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=30#abc');
        $this->assertTrue($pstyle->getLastPageUrl() === 'http://www.baidu.com/user/style?a=1&psizex=30#abc');
    }

    public function testStyl9()
    {
        $paginator = new Paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(NumPaginatorStyle::class)->newStyle();
        $styleArr = $pstyle->toArray();
        $this->assertTrue(count($styleArr['style']['numPageUrl']) === 5);

        $pstyle = $paginator->setStyleClass(NumPaginatorStyle::class)->newStyle(11);
        $styleArr = $pstyle->toArray();
        $this->assertTrue(count($styleArr['style']['numPageUrl']) === 11);

        $pstyle = $paginator->setStyleClass(NumPaginatorStyle::class)->newStyle();
        $pstyle->setNumPageMax(13);
        $styleArr = $pstyle->toArray();
        $this->assertTrue(count($styleArr['style']['numPageUrl']) === 13);

    }

}
