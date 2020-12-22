# haxibiao/cms

> haxibiao/cms CMS 能力，SEO 能力

## 导语

包含蜘蛛池管理，爬虫监控，站群站点地图，收录提交，收录查询，权重查询，收录监控，自动更新...

## 主要依赖

1. haxibiao-config 系统配置，APP+ASO 配置，SEO 配置，广告配置
2. haxibiao-content 内容分配到站群
3. haxibiao-dimension 维度分析报表系统

## 安装步骤

1. `composer.json`改动如下：
   在`repositories`中添加 vcs 类型远程仓库指向
   `http://code.haxibiao.cn/packages/haxibiao-cms`
2. 执行`composer require haxibiao/cms`
3. 第一次安装或更新版本, 执行`php artisan cms:install`,
4. 完成

### 如何完成更新？

> 远程仓库的 composer package 发生更新时如何进行更新操作呢？

1. 执行`composer update haxibiao/cms`

## GQL 接口说明

## Api 接口说明
