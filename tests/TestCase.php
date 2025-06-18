<?php
namespace hpage\tests;

use hehe\core\hpage\Pagination;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Pagination
     */
    protected $hpage;
    // 单个测试之前(每个测试方法之前调用)
    protected function setUp():void
    {
        $this->hpage = new Pagination();
    }

    // 单个测试之后(每个测试方法之后调用)
    protected function tearDown():void
    {

    }

    // 整个测试类之前
    public static function setUpBeforeClass():void
    {

    }

    // 整个测试类之前
    public static function tearDownAfterClass():void
    {

    }


}
