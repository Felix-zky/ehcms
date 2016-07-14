## 前端扩展（插件以及框架）说明
### 电脑端：

#### 框架：
> 前台和后台页面，大部分情况下都是基于了bootstrap3.3.5版本，以后可能会替换到4.0（个别特殊页面可能会基于其他框架，会在模板页面标注）。
> 
> 后台所有页面均支持自适应，前台大部分页面支持自适应，对应一些复杂且显示效果不允许对内容进行删减的情况下不支持自适应。

#### JavaScript：
1. jQuery 基础JavaScript框架
2. bootstrap 前端框架自带的js插件
3. layer 弹窗组件
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
16. jweixin 微信JS-SDK 公众号网页开发插件
17. 日期时间选择器（未选定）  
未完待续...

### 手机端：
#### 框架：
> 经过慎重考虑，对比了各个框架的优劣，目前项目的手机端前端框架采用阿里巴巴UED团队开发的SUI Mobile。SUI Mobile是基于framework7UI库，集成了很多实用的前端效果，内置了很多JS功能。

#### JavaScript：
1. Zepto 基础JavaScript框架（如果一定要使用jQuery也是可以的。官方给出的解决方案是：在加载jQuery后紧接着执行 var zepto = jQuery;）,jQuery与Zepto的基本功能类似，但也有区别。**使用jquery可能会有一些小麻烦**
2. SUI Mobile 前端框架自带js插件
3. layer mobile 弹窗组件（SUI自带了一些弹窗效果，个别页面可能会加载layer来实现不同样式的效果。）
4. laytpl 模板引擎
5. jQuery.cookie 前端cookie插件
6. lodash 常用功能合集
7. echarts 百度图表插件
8. Swiper 焦点图（轮播）插件