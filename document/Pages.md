## 分页生成方式说明
分页生成及获取数据的方法分为同步和异步两种，同步主要的目的是为了搜索引擎抓取，有利于SEO优化。异步的方式目的是增加用户体验，可以自由的通过各种方式提示用户分页正在发生，正在获取数据，同时还可以减少分页的查询次数，减少服务器压力（在没开启缓存的情况下），异步仅在第一次GET进入页面的时候获取一次分页，后期获取数据并不执行分页查询。

### 后台
+ 由于不需要搜索引擎抓取，所有使用全异步方式生成分页按钮组以及获取分页数据。
+ 使用laypage插件来执行这一功能。
+ 统一使用POST类型传递页码。
+ 使用hash方式做异步分页切换，这样可以使用回退键来返回上一页。** 这一点并不强制使用，也可以使用普通异步获取数据。**

### 前台
+ 列表页、搜索结果页、标签页、聚合页等需要搜索引擎收录的页面，必须使用同步跳转方式。
+ 同步跳转使用的是ThinkPHP自带的page分页方式，具体输出的分页按钮组可以使用配置（type参数，可以使用命名空间方式来跨模块调用生成类）自行设定，默认使用的是ThinkPHP自带的bootstrap生成类。可重载该类亦可自己重新制作，但必须导入think\Paginator并继承Paginator类，render方法是抽象的，必须被重载。
+ 评论、购物记录等无需搜索引擎抓取，可以使用异步方法生成及获取数据，异步方式具体参照后台。