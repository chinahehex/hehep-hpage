# hehep-hpage

## 介绍
- hehep-hpage 是一个PHP分页组件、
- 支持自定义分页器
- 支持自定义分页样式
## 安装
- **gitee下载**:
```
git clone git@gitee.com:chinahehex/hehep-hpage.git
```

- **github下载**:
```
git clone git@github.com:chinahehex/hehep-hpage.git
```
- 命令安装：
```
composer require hehex/hehep-hpage
```

## 基本示例
- 客户端分页
```php
use hehe\core\hpage\Pagination;

// 表单数据
$form = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
];

// 创建一个客户端分页器
$paginator = Pagination::queryPaginator($form);

// 主动设置每页显示条数(不接受客户端每页条数参数)
$paginator->setPageSize(10);

// 主动设置当前页码(不接受客户端页码参数)
$paginator->setCurrentPage(2);

// 设置总条数
$paginator->setTotalCount(3000);

// 获取总页数
$paginator->getTotalPage();

// 获取分页起始位置(用于数据库查询)
$paginator->getOffset();

// 获取分页条数(用于数据库查询)
$paginator->getLimit();

// 设置当前分页数据
$paginator->setData([]);

// 获取分页相关参数
$paginator->toArray();
  
```

- 内部分页
```php
use hehe\core\hpage\Pagination;

// 创建一个内部 分页器
$paginator = Pagination::paginator(1,10);

// 主动设置每页显示条数
$paginator->setPageSize(10);

// 主动设置当前页码
$paginator->setCurrentPage(2);

// 设置总条数
$paginator->setTotalCount(3000);

// 获取总页数
$paginator->getTotalPage();

// 获取分页起始位置(用于数据库查询)
$paginator->getOffset();

// 获取分页条数(用于数据库查询)
$paginator->getLimit();

// 设置当前分页数据
$paginator->setData([]);

// 获取分页相关参数
$paginator->toArray();
  
```

## 默认分页器
- 说明
```
类路径：\hehe\core\hpage\Paginator
功能:提供分页基本功能，封装了分页相关参数，支持自定义分页样式。
```

- 创建默认分页器
```php
use \hehe\core\hpage\Pagination;
use \hehe\core\hpage\Paginator;
// 创建一个默认分页器,默认每页显示10条数据,当前页码为1

$paginator = Pagination::paginator(1,10);
$paginator = new Paginator(1,10);

```

- 获取分页相关参数
```php
use \hehe\core\hpage\Pagination;
$paginator = Pagination::paginator(1,10);
$result = $paginator->toArray();

// 返回的结果为：
$result = [
    'pageSize'=>10,// 每页显示条数
    'currentPage'=>1,// 当前页码
    'totalCount'=>0,// 总条数
    'totalPage'=>0,// 总页数
    'data'=>[],// 当前页数据
];

```

- 设置/获取分页参数
```php
use \hehe\core\hpage\Pagination;
$paginator = Pagination::paginator();

// 每页显示条数(10)
$paginator->setPageSize(10);
$paginator->getPageSize();

// 当前页码(1)
$paginator->setCurrentPage(1);
$paginator->getCurrentPage();

// 总条数(3000)
$paginator->setTotalCount(3000);
$paginator->getTotalCount();

// 总页数(300)
$paginator->getTotalPage();

// 分页数据库起始位置,读取条数
$paginator->getOffset();
$paginator->getLimit();

// 设置当前分页数据
$paginator->setData([]);

// 获取分页相关参数
$paginator->toArray();

```

## 客户端分页器
- 说明
```
类路径：hehe\core\hpage\Pagination,继承\hehe\core\hpage\Paginator
功能:支持客户端分页参数，支持自定义分页样式,支持lastId模式,常用于api接口
```
- 创建客户端分页器
```php
use \hehe\core\hpage\Pagination;
// 表单数据
$formQuery = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
    'lastId'=>''// 当前页最后一条数据id
];
$paginator = Pagination::queryPaginator($formQuery);
```

- 获取分页相关参数
```php
use \hehe\core\hpage\Pagination;
$paginator = Pagination::paginator(1,10);
$result = $paginator->toArray();

// 返回的结果为：
$result = [
    'pageSize'=>10,// 每页显示条数
    'currentPage'=>1,// 当前页码
    'totalCount'=>0,// 总条数
    'totalPage'=>0,// 总页数
    'data'=>[],// 当前页数据
    'lastId'=>'',// 当前页最后一条数据id
    'queryLastVar'=>'',// 客户端lastId参数名称
    'pageVar'=>'',// 客户端页码参数名称
    'pageSizeVar'=>'',// 客户端每页条数参数名称
];

```

- 设置/获取分页参数
```php
use \hehe\core\hpage\Pagination;
$paginator = Pagination::queryPaginator();
// 设置客户端分页参数
$formQuery = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
];
$paginator->setQuery($formQuery);

// 主动设置每页显示条数(10),客户端传入的每页条数参数失效
$paginator->setPageSize(10);
$paginator->getPageSize();

// 主动设置当前页码(1),客户端传入的页码参数失效
$paginator->setCurrentPage(1);
$paginator->getCurrentPage();

```

- LastId模式
```php
use \hehe\core\hpage\Pagination;
$formQuery = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
    'lastId'=>2// 当前页最后一条数据id
];
$paginator = Pagination::queryPaginator($formQuery);

// 开启lastId模式，并设置数据端参数名称:"id",客户端参数名称:"lastId"
$paginator->asLastMode('id','lastId');

// 获取客户端lastId参数:2
$paginator->getQueryLastId();

$paginator->setData([
    ['id'=>1, 'name'=>'test1','roleId'=>1],
    ['id'=>2, 'name'=>'test2','roleId'=>3],
    ['id'=>3, 'name'=>'test3','roleId'=>4],
    ['id'=>4, 'name'=>'test4','roleId'=>4],
]);

// 获取数据端最后一条数据id:4
$paginator->getDataLastId();

// 获取是否需要查询总条数状态,当客户端lastId参数为空时，返回true
$paginator->getQueryCountStatus();
```

## 分页URL

- 独立设置分页URL
```php
use \hehe\core\hpage\Pagination;
$formQuery = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
];
$paginator = Pagination::queryPaginator($formQuery);
$style = $paginator->newStyle();

// 设置分页URL
$style->setPath('api/test?id=1');

// 带[PAGE]占位符URL
$style->setPath('api/test/[PAGE]?id=1');

$currentPageUrl = $style->getCurrentPageUrl();
// $currentPageUrl: api/test?id=1&page=2

```

- 全局URL生成器
```php
use \hehe\core\hpage\Pagination;

// 设置全局URL生成器
Pagination::setUriBuilder(function(string $uri,array $uriParams = []){
    // 自定义自己的URL生成规则
    // $uriParams 已经包含了分页参数
    // return Route::buildUrL($uri,$uriParams);
    return $uri . '?' . http_build_query($uriParams);
});

$formQuery = ['page'=>2,'psize'=>10];
$paginator = Pagination::queryPaginator($formQuery);
$style = $paginator->newStyle();
$style->setUrl('user/logs',['userId'=>1]);

// 获取当前页URL
$currentPageUrl = $style->getCurrentPageUrl();
// $currentPageUrl : user/logs?userId=1&page=2
```

## 分页样式器
- 说明
```
类路径：\hehe\core\hpage\styles\PaginatorStyle
```

- 默认分页样式器
```php
use \hehe\core\hpage\Pagination;
use hehe\core\hpage\styles\PaginatorStyle;
$formQuery = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
];
$paginator = Pagination::queryPaginator($formQuery);

// 分页器与样式绑定
$pstyle = $paginator->setStyleClass(PaginatorStyle::class);

// 创建分页器样式对象
$args = [];// 分页样式器构造参数
$style = $paginator->newStyle(...$args);

// 获取分页器样式参数
$style->toArray();

```

- 数字分页样式器
```php
use \hehe\core\hpage\Pagination;
use hehe\core\hpage\styles\NumPaginatorStyle;
$formQuery = [
    'page'=>1,// 当前页码
    'psize'=>10,// 每页显示条数
];
$paginator = Pagination::queryPaginator($formQuery);

// 分页器与样式绑定
$pstyle = $paginator->setStyleClass(NumPaginatorStyle::class);

// 创建分页器样式对象
$args = [6];// 分页样式器构造参数
$style = $paginator->newStyle(...$args);

// 获取分页器样式参数
$style->toArray();

```
