<?php
error_reporting(0);
include('./config.php');
include('./includes/function.php');
if ($REWRITE) {
    $pageName = explode("/", $_GET['uri'])[1];
    $cardID = explode("/", $_GET['uri'])[2];
    parse_str($_GET['args'], $QueryArr);
} else {
    $pageName = $_GET['page'];
}
if (empty($QueryArr) && empty($_GET['uri'])) {
    $QueryArr = $_GET;
    $cardID = $QueryArr['id'];
}
$templateMode = empty($QueryArr['_pjax']);
$siteTitle = getInfo('title');
switch ($pageName) {
    case "":
        include('./pages/homepage.php');
        listActive('home');
        break;
    case "submit":
        include('./pages/submit.php');
        listActive('submit');
        break;
    case "more":
        include('./pages/more.php');
        listActive('more');
        break;
    case "about":
        include('./pages/about.php');
        listActive('about');
        break;
    case "card":
        include('./pages/card.php');
        break;
    case "admin":
        if ($_COOKIE['loveway_token'] == md5($ADMIN_USER . $ADMIN_PASS . 'KAGAMINE WORLD!' . date('Y-m-d', time()))) {
            switch ($cardID) {
                case '':
                    include('./pages/admin/homepage.php');
                    break;
                case 'general':
                    include('./pages/admin/general.php');
                    break;
                case 'confession':
                    include('./pages/admin/confession.php');
                    break;
                default:
                    $templateMode = false;
                    include('./pages/404.php');
            }
        } else {
            include('./pages/login.php');
        }
        break;
    default:
        $templateMode = false;
        include('./pages/404.php');
}
echo titleChange();
if ($templateMode) {
    include('./includes/footer.php');
}
