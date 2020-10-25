<?php

require __DIR__ . '/header.php';
if ($_POST['count']) {
    $count = $_POST['count'];
}
$count = $xoopsModuleConfig['TopicCount'];
$GLOBALS['xoopsOption']['template_main'] = 'komments_index.html';
require XOOPS_ROOT_PATH . '/header.php';

//テーブル表題の言語情報を取得する
$header['place'] = _komments_place;
$header['topic'] = _komments_topic;
$header['poster'] = _komments_poster;
$header['reply'] = _komments_reply;
$header['read'] = _komments_read;
$header['date'] = _komments_date;

// オプション情報を取得する
$option['micon'] = $xoopsModuleConfig['Micon'];
$option['user'] = $xoopsModuleConfig['User'];
$option['footer'] = $xoopsModuleConfig['footer'];

$data = komments_main($count);
switch ($xoopsModuleConfig['Mode']) {
    case(1):
        foreach ($data['topic'] as $key => $value) {
            $topic = komments_minimum($value, $count);

            $mod[$key]['topics'] = komments_finalize($topic, $count, $xoopsModuleConfig['User'], $xoopsModuleConfig['catmax'], $xoopsModuleConfig['topicmax'], $xoopsModuleConfig['timestamp']);

            $mod[$key]['name'] = $data['footer']['name'][$key];

            $mod[$key]['img'] = $data['footer']['img'][$key];
        }

        $option['micon'] = 0;
        $option['footer'] = 0;
        $tempfile = 'db:komments_Content_' . $xoopsModuleConfig['Template'] . '.html';

        $template = new XoopsTpl();
        $template->assign('tempfile', $tempfile);
        $template->assign('option', $option);
        $template->assign('header', $header);
        $template->assign('module', $mod);
        $content = $template->fetch('db:komments_module.html');
        break;
    case(2):
    default:
        $topics = komments_arrayMarge($data['topic']);
        $topic = komments_finalize($topics, $count, $xoopsModuleConfig['User'], $xoopsModuleConfig['catmax'], $xoopsModuleConfig['topicmax'], $xoopsModuleConfig['timestamp']);
        $footer = $data['footer'];
        $footer['option']['row'] = $xoopsModuleConfig['footer_row'];

        $template = new XoopsTpl();
        $template->assign('option', $option);
        $template->assign('header', $header);
        $template->assign('topics', $topic);
        $template->assign('footer', $footer);
        $content = $template->fetch('db:komments_Content_' . $xoopsModuleConfig['Template'] . '.html');
}
$xoopsTpl->assign('content', $content);
$xoopsTpl->assign('rsson', $xoopsModuleConfig['rss']);
$xoopsTpl->assign('rss_img', XOOPS_URL . '/modules/komments/images/rss.gif');
$xoopsTpl->assign('rss_link', XOOPS_URL . '/modules/komments/backend.php');
$xoopsTpl->assign('rss', _komments_rss);
$xoopsTpl->assign('footer', _komments_footer);

require XOOPS_ROOT_PATH . '/footer.php';
