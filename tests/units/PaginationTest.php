<?php
namespace hpage\tests\units;
use hehe\core\hpage\paginators\Paginator;
use hehe\core\hpage\paginators\QueryPaginator;
use hehe\core\hpage\Pagination;
use hehe\core\hpage\styles\PaginatorStyle;
use hpage\tests\TestCase;

class PaginationTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    // 单个测试之后(每个测试方法之后调用)
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testQueryTotalPage()
    {
        $paginator = Pagination::queryPaginator(['page'=>1]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = Pagination::queryPaginator(['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::queryPaginator(['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::queryPaginator(['page'=>1],29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testDefaultTotalPage()
    {
        $paginator = Pagination::paginator(1);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = Pagination::paginator(1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::paginator(1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::paginator(1,29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testCreatePaginator()
    {
        $paginator = Pagination::createPaginator('paginator',1);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = Pagination::createPaginator('paginator',1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::createPaginator('paginator',1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::createPaginator('paginator',1,29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testCreateQueryTotalPage()
    {
        $paginator = Pagination::createPaginator('query',['page'=>1]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = Pagination::createPaginator('query',['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = Pagination::createPaginator('query',['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator =  Pagination::createPaginator('query',['page'=>1],29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testSetUrlBuilder()
    {
        // 设置生成
        PaginatorStyle::setUriBuilder(function (string $url,array $urlParams){
            return $url . '?' . http_build_query($urlParams);
        });

        $paginator = Pagination::paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPageVar('psizex')->setUrl('style');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'style?psizex=31');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'style?psizex=1');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'style?psizex=30');
        $this->assertTrue($pstyle->getNextPageUrl() === 'style?psizex=30');
        $this->assertTrue($pstyle->getLastPageUrl() === 'style?psizex=30');

    }


}
