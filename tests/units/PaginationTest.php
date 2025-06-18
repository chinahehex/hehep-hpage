<?php
namespace hpage\tests\units;
use hehe\core\hpage\paginators\Paginator;
use hehe\core\hpage\paginators\QueryPaginator;
use hehe\core\hpage\Pagination;
use hehe\core\hpage\styles\PaginatorStyle;
use hpage\tests\TestCase;

class PaginationTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();
    }

    // 单个测试之后(每个测试方法之后调用)
    protected function tearDown():void
    {
        parent::tearDown();
    }

    public function testQueryTotalPage()
    {
        $paginator = $this->hpage->queryPaginator(['page'=>1]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = $this->hpage->queryPaginator(['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->queryPaginator(['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->queryPaginator(['page'=>1],29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testDefaultTotalPage()
    {
        $paginator = $this->hpage->paginator(1);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = $this->hpage->paginator(1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->paginator(1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->paginator(1,29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testCreatePaginator()
    {
        $paginator = $this->hpage->createPaginator('paginator',1);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = $this->hpage->createPaginator('paginator',1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->createPaginator('paginator',1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->createPaginator('paginator',1,29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testCreateQueryTotalPage()
    {
        $paginator = $this->hpage->createPaginator('query',['page'=>1]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = $this->hpage->createPaginator('query',['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = $this->hpage->createPaginator('query',['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator =  $this->hpage->createPaginator('query',['page'=>1],29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testSetUrlBuilder()
    {
        // 设置生成
        PaginatorStyle::setUriBuilder(function (string $url,array $urlParams){
            return $url . '?' . http_build_query($urlParams);
        });

        $paginator =$this->hpage->paginator(31,10);
        $paginator->setTotalCount(300);
        $pstyle = $paginator->setStyleClass(PaginatorStyle::class)->newStyle();
        $pstyle->setPageVar('psizex')->setUrl('style');
        $this->assertTrue($pstyle->getCurrentPageUrl() === 'style?psizex=31');
        $this->assertTrue($pstyle->getFirstPageUrl() === 'style?psizex=1');
        $this->assertTrue($pstyle->getPrevPageUrl() === 'style?psizex=30');
        $this->assertTrue($pstyle->getNextPageUrl() === 'style?psizex=30');
        $this->assertTrue($pstyle->getLastPageUrl() === 'style?psizex=30');

    }

    public function testRetAlias()
    {
        $this->hpage->setOptions([
            'retAlias'=>['total'=>'total1','psize'=>'psize']
        ]);

        $paginator = $this->hpage->queryPaginator(['page'=>1]);
        $params = $paginator->toArray();

        $this->assertTrue(isset($params['total1']));
        $this->assertTrue(isset($params['psize']));
        $this->assertTrue(!isset($params['currentPage']));
    }

    public function testRetAlias1()
    {
        $this->hpage->setOptions([
            'retAlias'=>['total','psize'=>'pagesize']
        ]);

        $paginator = $this->hpage->queryPaginator(['page'=>1]);
        $params = $paginator->toArray();

        $this->assertTrue(isset($params['total']));
        $this->assertTrue(isset($params['pagesize']));
        $this->assertTrue(!isset($params['currentPage']));
    }

    public function testRetAlias2()
    {

        $paginator = $this->hpage->queryPaginator(['page'=>1]);
        $params = $paginator->toArray(['total','psize'=>'pagesize']);

        $this->assertTrue(isset($params['total']));
        $this->assertTrue(isset($params['pagesize']));
        $this->assertTrue(!isset($params['currentPage']));
    }


}
