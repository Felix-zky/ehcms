## 快速响应使用简介

### 一、参数的传递技巧
> 在功能设计时考虑到同步及异步响应函数调用所需参数不统一，故将功能参数接收做了一些特性调整，根据传递的内容灵活识别，减少参数传递量，但同时需要遵循以下几点使用方法。

情况一：  
```php
$msg = string, $data = '', $url = null
```  
仅对$msg做判断，如果属于语言包将从语言包中调用相应的内容。返回正确的情况下，只赋值msg。返回错误的情况下，同时将错误编码赋值给code。（错误语言包E-开头，正确语言包S-开头，具体可参照[服务端错误语言包](./ServerError.md)、[服务端正确语言包](./ServerSuccess.md)、[客户端错误语言包](./ClientError.md)、[客户端正确语言包](./ClientSuccess.md)）

情况二：
```php
$msg = array && $msg[isMsg] != TRUE, $data = '', $url = null
```  
当$msg为数组时，且isMsg不为TRUE时，识别为当前$msg内容属于$data的。会调用ResultMsgIsData函数对$msg和$data进行互换。
当isMsg为TRUE时，将不会互换。

情况三：
```php
$msg = string, $data = U-xxxx, $url = null
```  
$msg保持不变，$data将被识别为设置的是$url（正则检查是否为U-开头6数字结尾）。使用助手函数eh_url调用相应连接地址赋值给$url，$data赋空值。

情况四：
```php
$msg = array && $msg[isMsg] != TRUE, $data = U-xxxx, $url = null
```  
先执行$data将被识别为设置的是$url。会将$data前缀U-过滤掉，其余内容赋值给$url，$data赋空值。然后再执行$msg与$data的互换。