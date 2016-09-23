## 快速响应使用简介

### 参数的传递技巧
> 在功能设计时考虑到同步及异步响应函数调用所需参数不统一，故将功能参数接收做了一些特性调整，根据传递的内容灵活识别，减少参数传递量，但同时需要遵循以下几点使用方法。

**仅对一些特殊情况进行列举**
在正常情况下，$msg为字符串，$url为字符串-url地址（可带http或者/开头）或者url助手函数能够识别的地址表达式，$data为数组。

情况一：  
```php
$msg = string, $url = null, $data = ''
```  
仅对$msg做判断，如果属于语言包将从语言包中调用相应的内容。返回正确的情况下，只赋值msg。返回错误的情况下，同时将错误编码赋值给code。（错误语言包E-开头，正确语言包S-开头，具体可参照[服务端错误语言包](./ServerError.md)、[服务端正确语言包](./ServerSuccess.md)、[客户端错误语言包](./ClientError.md)、[客户端正确语言包](./ClientSuccess.md)）

情况二：
```php
$msg = array && $msg[isMsg] != TRUE, $url = null, $data = ''
```  
当$msg为数组时，且isMsg不为TRUE时，识别为当前$msg内容属于$data的。会调用ResultMsgIsData函数对$msg和$data进行互换。
当isMsg为TRUE时，将不会互换。

情况三：
```php
$msg = string, $url = 'U-xxxx', $data = ''
```  
$msg保持不变，$url将被识别为地址标识符，会使用eh_url助手函数对标识符进行解析。请参照[URL配置包](./UrlConfig.md)

情况四：
```php
$msg = string, $url = array, $data = ''
```  
$msg保持不变，$url赋值给$data，$url赋初始值（null）。

情况五：
```php
$msg = array && $msg[isMsg] != TRUE, $url = array, $data = ''
```  
先将$msg赋值给$data，后执行$url赋值给$data，但由于$data已经有值，将不会被覆盖。这个设置是确保$msg数据优先。

**综上所述，如果希望将$msg或者$url赋值给$data，必须将自身设置为数组。（建议保证$data为数组，就算只有一个值，也使用数组传递，统一类型可以防止在以后的升级中造成不必要的混乱。）**