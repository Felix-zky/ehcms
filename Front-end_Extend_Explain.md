## 前端扩展（插件以及框架）说明
**以下列出的类库是ehcms项目用到的全部类库，每个页面按需加载，并不会把全部的类库都加载进去，当你在扩展模板的时候需要注意相应的类库是否已加载。**

### 通用扩展：
电脑端以及手机端同时使用且必须加载的类库

1. requirejs 脚本模块化（为什么没用seajs，并不是说seajs不好，只是我们觉得requirejs更适合这个项目，当你扩展类库的时候，请使用AMD规范）
2. eh 官方辅助插件（主库必须加载，其他扩展库按需加载） [查看官方插件列表](./eh.md)

### 电脑端：
#### 框架：
> 前台和后台页面，大部分情况下都是基于了bootstrap3.3.5版本，以后可能会替换到4.0（个别特殊页面可能会基于其他框架，会在模板页面标注）。
> 
> 后台所有页面均支持自适应，前台大部分页面支持自适应，对应一些复杂且显示效果不允许对内容进行删减的情况下不支持自适应。

#### JavaScript：
1. jQuery 基础JavaScript框架
2. bootstrap 前端框架自带的js插件
3. layer 弹窗组件（基础常用）
4. laytpl 模板引擎
5. layPage 分页插件（支持同步刷新、异步切换、异步可后退location.hash）
6. jQuery.cookie 前端cookie插件
7. jQuery.lazyload 图片预加载
8. webUploader 百度编辑器
9. echarts 百度图表插件
10. lodash 常用功能合集
11. messenger 弹窗组件（主要做结果反馈，效果不错）
12. icheck 单选、复选框效果及功能增强
13. imagesLoad 图片加载完成判断
14. Swiper 焦点图（轮播）插件，多种展现样式可选，支持触控、3D效果、图片延时加载、单背景内容切换等等，适用于电脑端以及手机端。
15. Masonry 瀑布流
16. jquery.contextMenu 右键菜单管理
17. jquery.validate 表单验证
18. 日期时间选择器（未选定）  
未完待续...

### 手机端：
#### 框架：
> 经过慎重考虑，对比了各个框架的优劣，目前项目的手机端前端框架采用阿里巴巴UED团队开发的SUI Mobile。SUI Mobile是基于framework7UI库，集成了很多实用的前端效果，内置了很多JS功能。

#### JavaScript：
1. Zepto 基础JavaScript框架（如果一定要使用jQuery也是可以的。SUI Mobile官方给出的解决方案是：在加载jQuery后紧接着执行 var zepto = jQuery;）,jQuery与Zepto的基本功能类似，但也有区别。**使用jquery可能会有一些小麻烦**
2. SUI Mobile 前端框架自带js插件
3. layer mobile 弹窗组件（SUI自带了一些弹窗效果，个别页面可能会加载layer mobile来实现不同样式的效果。）
4. laytpl 模板引擎
5. jQuery.cookie 前端cookie插件
6. lodash 常用功能合集
7. echarts 百度图表插件
8. Swiper 焦点图（轮播）插件
9. jquery.validate 表单验证
10. jweixin 微信JS-SDK 公众号网页开发插件