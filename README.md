QBoke
=====

QBoke 是一个轻量级的博客生成系统，基于 PHP、Markdown 和 git。你可以用 Markdownn 写博客，用 git 管理它们，然后用 QBoke 发布和展示它们。

QBoke 没有复杂花哨的管理后台和在线编辑器。你只需很少的几项配置，然后选个喜欢的文本编辑器就可以专注在写作上了。

为什么需要 QBoke ？
----------------

QBoke 是为了满足如下需求而生的：

1. 静态化

   博客在静态化之后可以达到飞一般的速度，用户体验得到大幅提升。
   
2. 支持 Markdown

   Markdown 简洁优雅的格式，让你忘掉复杂的排版，而专心于写作本身。你可以使用任何你喜欢的文本编辑器来写 Markdown 格式的文章。
   
3. 支持 git

   git 可以高效的管理你的博客以及你做过的所有修改记录。无需备份，也不再担心会丢失自己辛苦劳动的成果。
   
4. 自动发布

   我希望，当我写完博客之后，内容会立即展示在我的博客网站。不要让我再登录到网站，把文章拷贝到在线编辑器，然后花半天的时间进行排版。
   
5. 不依赖某一特定服务商

   还记得雅虎宣布停止邮件服务之后的痛苦么？即使依赖服务商，那也得能无缝的从各个服务商之间进行切换。

QBoke 是怎样工作的？
-----------------

在发布博客的整个流程，你需要做的只有编写博客并提交到 git 仓库。git 仓库在你提交新的博客或修改时会自动通知 QBoke。然后，剩下的所有事情都交给 QBoke 就行了。

QBoke 会帮你做这些事情：

1. 从 git 仓库拉取最新的修改；
2. 把 Markdown 格式的内容转换为 HTML 格式；
3. 套用一款漂亮的的主题；
4. 应用若干插件，对内容进行修饰；
5. 生成静态的页面。

以上步骤仅在每次提交新内容之后执行一次。

git 是怎么自动通知 QBoke 最新更新的呢？答案是 POST 钩子。你可以经过配置让 git 在获取到你的提交之后向一个指定的链接发送一个 POST 请求。如果这个指定的链接是 QBoke 的链接，那 QBoke 就知道你提交了新的内容。

更多内容
-------

如何安装 QBoke？可以参考这篇[快速安装说明](http://qboke.org/getting-started.html)。

如果想更详细的了解 QBoke ，欢迎查看本站的其他文章。

如果你有什么意见或建议，或者发现了 QBoke 的问题，欢迎访问如下链接进行提交：

<https://github.com/solicomo/qboke/issues>

或者通过一下方式进行联系：

Email: <soli@cbug.org>

Twitter: <https://twitter.com/solicomo>
