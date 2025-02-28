<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <title><?php $subPageName = $_GET['page'];
            if (!empty(getInfo($subPageName))) echo getInfo($subPageName) . ' - ';
            echo $siteTitle ?></title>
    <meta name="keywords" content="<?php echo getInfo('keywords') ?>">
    <meta name="description" content="<?php echo getInfo('description') ?>">
    <link rel="stylesheet" href="/static/mdui/css/mdui.min.css" />
    <link rel="stylesheet" href="/static/css/main.css" />
    <script src="/static/js/cookie.main.js"></script>
    <script src="/static/mdui/js/mdui.min.js"></script>
    <script src="/static/js/jquery.min.js"></script>
    <script src="/static/js/jquery.pjax.js"></script>
    <script src="/static/js/jquery.md5.js"></script>
    <script src="/static/js/clipboard.min.js"></script>
    <script src="/static/js/main.js"></script>
    <script charset="UTF-8" id="LA_COLLECT" src="//sdk.51.la/js-sdk-pro.min.js"></script>
<script>LA.init({id: "siteid",ck: "sitekey"})</script>
</head>

<body class="mdui-drawer-body-left mdui-bottom-nav-fixed mdui-appbar-with-toolbar mdui-theme-primary-blue-grey mdui-theme-accent-pink mdui-theme-layout-auto mdui-loaded">
    <header id="appbar" class="mdui-appbar mdui-appbar-fixed">
        <audio src="<?php echo getInfo('audio') ?>" loop autoplay>
            抱歉...您的浏览器暂不支持audio标签哦！
        </audio>
        <div class="mdui-progress" id="isLoading">
            <div class="mdui-progress-indeterminate"></div>
        </div>
        <div class="mdui-toolbar mdui-color-theme">
            <span class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white " onclick="inst.toggle();"><i class="mdui-icon material-icons">menu</i></span>
            <a href="../" class="mdui-typo-headline mdui-hidden-xs"><?php echo $siteTitle; ?></a>
            <div class="mdui-toolbar-spacer"></div>
            <button onclick="search()" mdui-tooltip="{content: '搜索'}" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"><i class="mdui-icon material-icons">search</i></button>
        </div>
    </header>
    <div class="mdui-drawer" id="main-drawer">
        <div class="mdui-list " mdui-collapse="{accordion: true}" style="margin-bottom: 76px;">
            <div class="mdui-list">
                <a href="/" id="homePage" class="mdui-list-item mdui-ripple" style="border-radius: 16px;">
                    <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                    <div class="mdui-list-item-content">主页</div>
                </a>
                <a href="<?php if ($REWRITE) echo '/';
                            else echo '/?page='; ?>submit" id="submitPage" class="mdui-list-item mdui-ripple" style="border-radius: 16px;">
                    <i class="mdui-list-item-icon mdui-icon material-icons">favorite</i>
                    <div class="mdui-list-item-content"><?php echo getInfo('submit') ?></div>
                </a>
                <!--<a href="<?php if ($REWRITE) echo '/';
                            else echo '/?page='; ?>more" id="morePage" class="mdui-list-item mdui-ripple" style="border-radius: 16px;">
                    <i class="mdui-list-item-icon mdui-icon material-icons">tag_faces</i>
                    <div class="mdui-list-item-content"><?php echo getInfo('more') ?></div>
                </a>-->
                <a href="<?php if ($REWRITE) echo '/';
                            else echo '/?page='; ?>about" id="aboutPage" class="mdui-list-item mdui-ripple" style="border-radius: 16px;">
                    <i class="mdui-list-item-icon mdui-icon material-icons">code</i>
                    <div class="mdui-list-item-content"><?php echo getInfo('about') ?></div>
                </a>
            </div>
            <div class="copyright">
                <div class="mdui-typo">
                    <p class="mdui-typo-caption-opacity">© 2022 Everyone</p>
                    <p class="mdui-typo-caption-opacity">Powered by <a href="https://mdui.org" target="_blank">MDUI</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="mdui-container" id="pjax-container" style="max-width: 800px;">