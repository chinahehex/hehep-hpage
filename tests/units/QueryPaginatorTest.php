<?php
namespace hpage\tests\units;
use hehe\core\hpage\paginators\Paginator;
use hehe\core\hpage\paginators\QueryPaginator;
use hpage\tests\TestCase;

class QueryPaginatorTest extends TestCase
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

    public function testTotalPage()
    {
        $paginator = new QueryPaginator(['page'=>1]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = new QueryPaginator(['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = new QueryPaginator(['page'=>1],30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = new QueryPaginator(['page'=>1],29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testPageSize()
    {
        $paginator = new QueryPaginator(['page'=>1],11);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 11);

        $paginator = new QueryPaginator(['page'=>1],10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 10);
    }

    public function testPageSize1()
    {
        $paginator = new QueryPaginator(['page'=>1,'psize'=>11]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 11);

        $paginator = new QueryPaginator(['page'=>1,'psize'=>10],12);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 12);
    }

    public function testPageNum()
    {
        $paginator = new QueryPaginator(['page'=>1]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getCurrentPage() === 1);

        $paginator = new QueryPaginator(['page'=>0]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getCurrentPage() === 1);

        $paginator = new QueryPaginator(['page'=>3]);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getCurrentPage() === 3);
    }

    public function testOffset()
    {
        $paginator = new QueryPaginator(['page'=>1],10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getOffset() === 0);

        $paginator = new QueryPaginator(['page'=>2],10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getOffset() === 10);

        $paginator = new QueryPaginator(['page'=>4],10);
        $paginator->setTotalCount(3000);

        $this->assertTrue($paginator->getOffset() === 30);
    }

    public function testLimit()
    {
        $paginator = new QueryPaginator(['page'=>1],10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getLimit() === 10);
    }

    public function testLastId()
    {
        $paginator = new QueryPaginator(['page'=>1],10);
        $paginator->asLastMode();

        $paginator->setData([
            ['id'=>1, 'name'=>'test'],
            ['id'=>2, 'name'=>'test'],
            ['id'=>3, 'name'=>'test'],
        ]);

        $this->assertTrue($paginator->getDataLastId() === 3);

        $paginator = new QueryPaginator(['page'=>1],10);
        $paginator->asLastMode('roleId');
        $paginator->setData([
            ['id'=>1, 'name'=>'test','roleId'=>1],
            ['id'=>2, 'name'=>'test','roleId'=>3],
            ['id'=>3, 'name'=>'test','roleId'=>4],
        ]);

        $this->assertTrue($paginator->getDataLastId() === 4);
    }

    public function testPageVar()
    {
        $paginator = new QueryPaginator(['pagex'=>1],10);
        $paginator->setPageVar('pagex');

        $paginator = new QueryPaginator(['pagex'=>1],11);
        $paginator->setPageVar('pagex');
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 11);

        $paginator = new QueryPaginator(['pagex'=>1],10);
        $paginator->setPageVar('pagex');
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 10);

    }

//    public function testToArray()
//    {
//        $paginator = new QueryPaginator(['psize'=>1],10);
//        $paginator->setTotalCount(3000);
//        var_dump($paginator->toArray());
//        $this->assertTrue($paginator->toArray() === [
//            'pageSize'=>10,
//            'totalPage'=>300,
//            'totalCount'=>3000,
//            'currentPage'=>1,
//            'data'=>null,
//                'lastId'=>null,
//        ]);
//
//    }

    public function testLastIdMode()
    {
        // 没有lastId,第一页读取
        $paginator = new QueryPaginator(['page'=>1],6);
        $paginator->asLastMode();

        $paginator->setData([
            ['id'=>1, 'name'=>'test1','roleId'=>1],
            ['id'=>2, 'name'=>'test2','roleId'=>3],
            ['id'=>3, 'name'=>'test3','roleId'=>4],
            ['id'=>4, 'name'=>'test4','roleId'=>4],
            ['id'=>5, 'name'=>'test5','roleId'=>4],
            ['id'=>6, 'name'=>'test6','roleId'=>4],
        ]);

        $this->assertTrue($paginator->getDataLastId() === 6);
        $this->assertTrue($paginator->isQueryCount() === true);
    }

    public function testLastIdMode1()
    {
        // 没有lastId,第一页读取
        $paginator = new QueryPaginator(['page'=>1,'lastId'=>3],6);
        $paginator->asLastMode('id','lastId');

        $paginator->setData([
            ['id'=>1, 'name'=>'test1','roleId'=>1],
            ['id'=>2, 'name'=>'test2','roleId'=>3],
            ['id'=>3, 'name'=>'test3','roleId'=>4],
            ['id'=>4, 'name'=>'test4','roleId'=>4],
            ['id'=>5, 'name'=>'test5','roleId'=>4],
            ['id'=>6, 'name'=>'test6','roleId'=>4],
        ]);

        $this->assertTrue($paginator->getDataLastId() === 6);
        $this->assertTrue($paginator->getQueryLastId() === 3);
        $this->assertTrue($paginator->isQueryCount() === false);
    }

    public function testLastIdMode2()
    {
        // 没有lastId,第一页读取
        $paginator = new QueryPaginator(['page'=>1,'lastId'=>4],6);
        $paginator->asLastMode('id','lastId');
        $paginator->asQueryCount();
        $paginator->setData([
            ['id'=>1, 'name'=>'test1','roleId'=>1],
            ['id'=>2, 'name'=>'test2','roleId'=>3],
            ['id'=>3, 'name'=>'test3','roleId'=>4],
            ['id'=>4, 'name'=>'test4','roleId'=>4],
            ['id'=>5, 'name'=>'test5','roleId'=>4],
            ['id'=>6, 'name'=>'test6','roleId'=>4],
        ]);
        $this->assertTrue($paginator->getDataLastId() === 6);
        $this->assertTrue($paginator->getQueryLastId() === 4);
        $this->assertTrue($paginator->isQueryCount() === true);
    }


}
