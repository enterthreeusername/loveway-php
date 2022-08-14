<?php
if ($templateMode) {
    include('./includes/header.php');
}
?>
<br /><br />
<div class="mdui-card mdui-hoverable" style="border-radius: 16px">
    <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">关于本站</div>
        <div class="mdui-card-primary-subtitle">ABOUT SITE</div>
    </div>
    <div class="mdui-card-content">
        <div class="mdui-typo">
            <?php echo getInfo('about_content') ?>
            <br><br>
            <div class="mdui-divider"></div>
            <div class="mdui-card-primary-subtitle">
            <a href=https://github.com/enterthreeusername/loveway-php target="_black">GitHub</a><br>
            </div>
        </div>
    </div>
</div>
<br /><br />