# NyaBot

_✨ 一个简单,极易上手的基于PHP以及 [Swoole](https://www.swoole.com/) 的 [go-cqhttp](https://github.com/Mrs4s/go-cqhttp/) 简单WebSocketSDK ✨_  

## 🎈已经实现接口

<details>
<summary>已实现群聊接口</summary>

- [x] 发送群聊消息

- [x] 发送群聊回复消息

- More TODO...(咕咕咕)

</details>

<details>
<summary>已实现私聊接口</summary>

- [x] 发送私聊消息

- [x] 发送私聊回复消息

- More TODO...(咕咕咕)

</details>

## ⚙️快速部署
1.下载[swoole-cli](https://www.swoole.com/download)对应的系统版本

2.将此仓库Clone/Download下来
``` code
git clone https://github.com/Mxmilu666/NyaBot.git
```
3.在/inc/config.php中配置WS服务器和Token

4.执行
``` code
./swoole-cli bot.php
```
~~5.点一个stars~~

## 📁文件目录
``` code
.
|-- bot.php //主程序
|-- inc
|   |-- config.php //配置文件
|   `-- core.class.php //核心参数库
|-- class
|   `-- example.class.php //全局函数示例
`-- plugins
    `-- example.php //简单的示例(等我有空了补一个详细的(因为懒))

```

## 📖许可证
项目采用`Apache-2.0 license`协议开源

## 🫂感谢
[Swoole](https://www.swoole.com/)提供的高性能PHP协程框架

[1626424216](https://github.com/1626424216)提供的大部分框架代码和思路

### 这个项目只是开源我正在使用的框架,不一定可以满足所有人的需求,但是欢迎大家Pr(逃
