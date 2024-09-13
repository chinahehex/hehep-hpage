<?php
namespace hpage\tests\units;
use hehe\core\hpage\paginators\Paginator;
use hpage\tests\TestCase;

class ExampleTest extends TestCase
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

    public function testTotalPage()
    {
        $paginator = new Paginator(1);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 300);

        $paginator = new Paginator(1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = new Paginator(1,30);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 100);

        $paginator = new Paginator(1,29);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getTotalPage() === 104);
    }

    public function testPageSize()
    {
        $paginator = new Paginator(1,11);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 11);

        $paginator = new Paginator(1,0);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getPageSize() === 10);
    }

    public function testPageNum()
    {
        $paginator = new Paginator(1);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getCurrentPage() === 1);

        $paginator = new Paginator(0);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getCurrentPage() === 1);

        $paginator = new Paginator(3);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getCurrentPage() === 3);
    }

    public function testOffset()
    {
        $paginator = new Paginator(1,10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getOffset() === 0);

        $paginator = new Paginator(2,10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getOffset() === 10);

        $paginator = new Paginator(4,10);
        $paginator->setTotalCount(3000);

        $this->assertTrue($paginator->getOffset() === 30);
    }

    public function testLimit()
    {
        $paginator = new Paginator(1,10);
        $paginator->setTotalCount(3000);
        $this->assertTrue($paginator->getLimit() === 10);
    }

//    public function testToArray()
//    {
//        $paginator = new Paginator(1,10);
//        $paginator->setTotalCount(3000);
//        $this->assertTrue($paginator->toArray() === [
//            'pageSize'=>10,
//            'totalPage'=>300,
//            'totalCount'=>3000,
//            'currentPage'=>1,
//            'data'=>null,
//        ]);
//
//    }
}
