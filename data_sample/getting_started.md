<!--
date   : 2014-04-16 23:33:03
title  : 开始使用 QBoke
slug   : getting-started
author : Soli
format : markdownex
tags   : [QBoke, Documents]
excerpt: >
  让我们开始使用 QBoke 吧。在这篇文章中，你将了解到如何安装、配置 QBoke，
  并用它来展示你用 git 管理的博客。

-->
开始使用 QBoke
==============

让我们开始使用 QBoke 吧。在这篇文章中，你将了解到如何安装、配置 QBoke，  并用她来展示你用 git 管理的博客。

安装
----

QBoke 使用 composer 作为包管理器，管理相关的依赖关系，让安装升级过程更加惬意。composer 是当前非常流行的 PHP 包管理器。你肯定想去[官方网站](http://getcomposer.org/)了解一下她。

使用 QBoke ，需要先安装 composer ，然后用 composer 安装 QBoke。这个过程非常简单。

### 安装 composer

请查看 [Composer 文档](http://getcomposer.org/doc/00-intro.md) 中相关部分，安装 Composer。

如果你已经等不及了，可以直接执行下面的命令进行安装：

> $ curl -sS https://getcomposer.org/installer | php

> $ sudo mv composer.phar /usr/local/bin/composer

### 安装 QBoke

接下来，让我们用 composer 安装 QBoke。只需在网站的根路径下执行如下命令：

> $ composer create-project soli/qboke . 

注意命令最后有个点，并且点的两侧各有个空格。

稍等一会，命令执行完毕后，你就会在当前路径下发现 config.php、index.php 等文件以及 public、src、data 等文件夹。

这样，QBoke 就已经安装成功了。但在进行正确的配置之前，QBoke 还不能正常工作。

配置
----

### 配置你的 WebServer

配置你的 WebServer（你也是用的 nginx 的吧？）的 root ，指向 QBoke 的 public 目录。

具体操作步骤请参照相应 WebServer 的官方文档。

### 配置 QBoke

在 QBoke 能工作之前，你得先告诉它去哪里取你的博客。因为你的博客是用 git 管理的，那就要告诉 QBoke 你的博客的 git 仓库地址。

用任何你喜欢的编辑器打开刚才安装后的 config.php 文件，可以看到已经有个例子了。直接修改这个文件就行。

如果你的 git 仓库是个公共仓库，那你只需修改 remote 字段为你的仓库地址就算配置完成了。你可以直接跳到下一步。

如果你的 git 仓库是私有的，或者不支持 http 协议，那就需要为 QBoke 指定一个私钥文件，并把对应的公钥加入到 git 仓库的部署密钥列表（deploy keys）里。

为 QBoke 指定私钥文件，只需修改 config.php 文件中的 pkey 字段为你的私钥文件地址即可。最好是绝对路径。

对当前两大 git 托管服务商 GitHub 和 Bitbucket 部署密钥的操作方式基本一样。具体可以参照下面的官方文档。以后我也会在另一篇博文里对这进行详细介绍。

* [Managing deploy keys (Github)](https://help.github.com/articles/managing-deploy-keys)
* [Use deployment keys (Bitbucket)](https://confluence.atlassian.com/display/BITBUCKET/Use+deployment+keys)

所有配置都搞定之后，我们离成功仅有一步之遥了。

获取文章
-------

为了让 QBoke 更好的工作，我们还需要告诉它关于我们博客的其他一些额外信息，并且博客的目录组织结构也最好进行细微调整。当然了，现在不是详细讲这些的时候。

为了快速部署，我们就简单直接点吧。

把 data_sample 目录下的 .site 和 about.md 加入到你的 git 仓库根目录下，并推送到服务器。然后，访问如下链接：

http://yourdomain.com/sync.php?key=your_secret_key_for_sync

你在访问这个链接之后，QBoke 做了这么几件事儿：

1. 从你的 git 仓库把你的博客 pull 到它的 data 目录下；
2. 搜索并加载 .site 里面的信息；
3. 扫描所有的 .md 文件，将其转换为 html 静态页面存到 public 目录下；
4. 把所有非 .md 文件直接拷贝到 public 目录下的相应位置。

当然，这些你不需要关心啦。因为你的博客已经全部给你转换好了。

成功了
-----

此时再访问你的博客首页，你就会发现一个漂亮的博客就这样诞生了。

再来回顾一下我们都做了什么：

1. 安装了 composer；
2. 用 composer 安装了 QBoke；
3. 配置了 WebServer；
4. 为 QBoke 指定 git 仓库地址；
5. 访问了一个 URL ；

如果你的 git 仓库是私有的，你还进行了额外的两步：

1. 部署密钥（deploy keys)；
2. 为 QBoke 指定私钥文件路径；

是不是很简单？呃，也不是那么简单么。。。但是，以后发博客肯定很简单！只需两步：

1. 写博客；
2. 提交博客到 git 仓库。

对，就这两步！并且都是在你本机操作，根本不需要打开浏览器，然后登录管理后台，然后blabla。。。

呃，好吧，为了达到这个效果，我们还得做点额外的工作。请往下看。

自动更新
-------

为了让 QBoke 知道你什么时候提交了新的博客，我们需要有一种方法通知它。这种方法就是 git 的 hook 。聪明的你肯定早就知道了，是吧。

把上面我们访问的那个链接加到 git 的 POST hook 里，每当我们向仓库提交了一篇新博客或更新了某篇博客，那个链接都会被调用，也就是说，QBoke 都会默默的去做很多工作来保证你网站上的博客是最新的。

说了这么多，怎么给 git 加 hook 呢？聪明的你怎么会问这个问题呢？好吧，我还是贴上 GitHub 和 Bitbucket 的文档链接吧：

1. [Webhooks (GitHube)](https://developer.github.com/webhooks/);
2. [Manage Bitbucket hooks (Bitbucket)](https://confluence.atlassian.com/display/BITBUCKET/Manage+Bitbucket+hooks)

未完待续
-------

QBoke 的快速安装教程到这就结束了。但对于 QBoke 我们还有很多东西要讲。接下来的几篇博文，我会从各个角度以各种姿势把 QBoke 呈现大家。

晚安。
