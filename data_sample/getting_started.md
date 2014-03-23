<!--
date   : 2013-11-06 23:04:24
title  : 开始使用 QBoke
slug   : getting-started
author : Soli
format : markdownex
tags   : [QBoke, Documents]
excerpt: >
  让我们开始使用 QBoke 吧。在这篇文章中，你将了解到如何安装、配置 QBoke，
  并用她来展示你用 git 管理的博客。

-->
开始使用 QBoke
==============

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

> $ composer create-project soli/qboke . --prefer-dist

注意命令中间有个点，并且点的两侧各有个空格。

稍等一会，命令执行完毕后，你就会在当前路径下发现 composer.json、index.php 等文件以及 include、plugins、themes 等文件夹。

这样，QBoke 就已经安装成功了。但在进行正确的配置之前，QBoke 还不能正常工作。

配置
----

### 配置路径

所有配置都搞定之后，我们离成功仅有一步之遥了。

获取文章
-------


成功了
-----

自动更新
-------
