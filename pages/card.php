<?php
if ($templateMode) {
    include('./includes/header.php');
}
?>
<br /><br />

<?php
try {
    $pdo = pdoConnect();
    $stmt = $pdo->prepare("select * from loveway_data where id = ?");
    $stmt->bindValue(1, $cardID);
    if ($stmt->execute()) {
        $rows = $stmt->fetchAll();
        $row = $rows[0];
        if (empty($row)) {
            if ($REWRITE) {
                $pageNotFound = "/404";
            } else {
                $pageNotFound = "/?page=404";
            }
            exit("<script> setTimeout(function () { $.pjax({ url: '" . $pageNotFound . "', container: '#pjax-container' }); }, 10) </script>");
        }
?>
        <div class="mdui-card mdui-hoverable" style="border-radius: 16px;">
            <div class="mdui-card-header">
                <img class="mdui-card-header-avatar" src="https://q1.qlogo.cn/g?b=qq&s=640&nk=<?php echo $row['contact']; ?>" />
                <div class="mdui-card-header-title"><?php echo $row['confessor']; ?></div>
                <div class="mdui-card-header-subtitle"><?php echo $row['time']; ?></div>
            </div>
            <div class="mdui-card-media">
                <?php
                if (!empty($row['image'])) {
                ?>
                    <img style="max-height: 1000px" onclick="if($(this).attr('origin-src') == undefined) { window.open($(this).attr('src')) } else { window.open($(this).attr('origin-src')) }" onerror="randomImage()" src="<?php echo $row['image']; ?>" />
                <?php
                } else {
                ?>
                    <div class="mdui-divider"></div>
                <?php } ?>
                <div class="mdui-card-menu">
                    <a target="_blank" style="color:#4F4F4F" href="
                    <?php
                    if ($REWRITE) {
                        echo "/";
                    } else {
                        echo '/';
                    }
                    ?>" class="mdui-btn mdui-btn-icon mdui-float-right">
                        <i class="mdui-icon material-icons">arrow_back</i>
                    </a>
                </div>
            </div>
            <div class="mdui-card-primary">
                <div class="mdui-card-primary-title">To <?php echo $row['to_who']; ?></div>
                <div class="mdui-card-primary-subtitle">
                    <?php echo $row['introduction']; ?>
                </div>
            </div>
            <div class="mdui-card-content">
                <?php echo $row['content']; ?>
                <br><br>
                <div class="mdui-card" style="border-radius: 16px;">
                    <div class="mdui-card-primary">
                        <div class="mdui-card-primary-title">发表您的评论</div>
                        <div class="mdui-card-primary-subtitle">可以发表您的感想以及感受哦！</div>
                    </div>
                    <div class="mdui-card-content">
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">您的昵称</label>
                            <input placeholder="张三" id="nickname" class="mdui-textfield-input" type="text" />
                        </div>
                        <div class="mdui-textfield">
                            <label class="mdui-textfield-label">你要说...</label>
                            <textarea id="content" class="mdui-textfield-input" rows="4" placeholder="哇，好棒好棒！"></textarea>
                        </div>
                        <div class="mdui-card-primary-subtitle">若下方没有验证码卡片请刷新或重进页面后再留言</div>
                        <div id="robot"></div>
                    </div>
                    <div class="mdui-card-actions">
                        <button id="submitbtn" style="border-radius: 8px" class="mdui-btn mdui-color-theme-accent mdui-ripple mdui-float-right" onclick="commentSubmit()">
                            评论
                        </button>
                    </div>
                </div>
                <br>
                <div class="mdui-card" id="commentBoxMain" style="border-radius: 16px;">
                    <div class="mdui-card-primary">
                        <div class="mdui-card-primary-title">所有评论</div>
                        <div class="mdui-card-primary-subtitle">这些都是给信的主人的评论啦！</div>
                    </div>
                    <div id="commentBox" class="mdui-card-content">
                        <?php
                        $commentArr = json_decode($row['comment'], true);
                        $commentNum = count($commentArr);
                        if ($commentNum == 0) {
                            echo "<script>$('#commentBoxMain').hide();</script>";
                        }
                        for ($i = 0; $i < $commentNum; $i++) {
                            echo '<div class="mdui-card-primary-subtitle">' . $commentArr[$i]['nickname'] . '在 ' . date("Y-m-d H:i:s", intval($commentArr[$i]['time'])) . ' 的评论</div><br>';
                            echo nl2br($commentArr[$i]['content']);
                            if ($i != $commentNum - 1) {
                                echo '<br><br><div class="mdui-divider"></div><br>';
                            }
                        }
                        ?>
                        <br><br>
                    </div>
                </div>
            </div>
            <div class="mdui-card-actions">
                <a class="copy mdui-btn mdui-btn-icon mdui-float-right" href="javascript:void(0);" data-clipboard-text="
                            <?php
                            echo get_http_type() . $_SERVER['SERVER_NAME'];
                            if ($REWRITE) {
                                echo "/card/" . $row['id'];
                            } else {
                                echo '/?page=card&id=' . $row['id'];
                            }
                            ?>
                            "><i class="mdui-icon material-icons">share</i></a>
                </a>
                <div id="like-<?php echo $row['id'] ?>" class="mdui-float-right mdui-card-primary-subtitle">
                    <?php echo $row['favorite'] ?>
                </div>
                <button style="color:#4F4F4F" class="mdui-btn mdui-btn-icon mdui-float-right" onclick="like('<?php echo $row['id'] ?>')">
                    <i class="mdui-icon material-icons">favorite</i>
                </button>
            </div>
        </div>

<?php
    } else {
        return 'database connection failed';
    }
} catch (Exception $e) {
    return 'database connection failed';
}
?>
<script src="https://www.recaptcha.net/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=cn" async defer></script>
<script>
    function commentSubmit() {
        var responseToken = grecaptcha.getResponse(widgetId);
    if (responseToken.length == 0) {
        mdui.dialog({title: '验证失败',
        content:'请完成验证码后再试',
        buttons: [{
                    text: '确定'
                }]
        });
    } else {
            requestApi("comment", {
            id: <?php echo $cardID ?>,
            nickname: $("#nickname").val(),
            content: $("#content").val(),
            timestamp: this.timestamp = Date.parse(new Date()) / 1000
            }, false, true, true, "#submitbtn")
        }
    }

    function like(id) {
        mdui.dialog({
            title: '请输入右侧图片中的验证码',
            content: '<center><div class="mdui-row"> <div class="mdui-col-xs-9"> <div class="mdui-textfield"> <input class="mdui-textfield-input" id="vCode" type="text" placeholder="请输入您的答案" /></div> </div> <div class="mdui-col-xs-3"> <img style="position: relative;top:15px" id="vcode" src="/api/vcode.php" /> </div> </div></center>',
            modal: true,
            buttons: [{
                    text: '取消'
                },
                {
                    text: '确认',
                    onClick: function(inst) {
                        requestApi("favorite", {
                            id: id,
                            vCode: $("#vCode").val(),
                            timestamp: this.timestamp = Date.parse(new Date()) / 1000
                        }, false, true, true, "")
                    }
                }
            ]
        });

    }

    $(function() {
        var clipboard = new ClipboardJS('.copy');
        clipboard.on('success', function(e) {
            mdui.snackbar({
                message: '复制表白卡片地址成功！',
                position: 'right-top'
            });
        });
        clipboard.on('error', function(e) {
            mdui.snackbar({
                message: '复制表白卡片地址失败！请尝试手动复制！',
                position: 'right-top'
            });
        });
    });
var widgetId;
var onloadCallback = function () {
    widgetId = grecaptcha.render('robot', {
        'sitekey': '$yoursitekey', 
        'theme': 'light',
        'size': 'normal',
    });
};
function getResponseFromRecaptcha() {
    var responseToken = grecaptcha.getResponse(widgetId);
    if (responseToken.length == 0) {
        alert("验证失败");
    } else {
        alert("验证通过");
        
    }
};
</script>