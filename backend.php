<?php

require __DIR__ . '/header.php';
if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);
if (!$tpl->is_cached('db:komments_rss.html')) {
    $data = komments_main(10);

    $topic = komments_arrayMarge($data['topic']);

    $topics = komments_finalize($topic, 10, 0, 0, 0, 'D, j M Y H:i:s T');

    if (is_array($topic)) {
        $tpl->assign('channel_title', xoops_convert_encoding(htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES)));

        $tpl->assign('channel_link', XOOPS_URL . '/');

        $tpl->assign('channel_desc', xoops_convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));

        $tpl->assign('channel_lastbuild', xoops_convert_encoding(formatTimestamp(time(), 'D, j M Y H:i:s T')));

        $tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);

        $tpl->assign('channel_editor', $xoopsConfig['adminmail']);

        $tpl->assign('channel_category', 'New Infomation');

        $tpl->assign('channel_generator', XOOPS_VERSION . ' + komments 0.6.1');

        $tpl->assign('channel_language', _LANGCODE);

        $tpl->assign('image_url', XOOPS_URL . '/images/logo.gif');

        $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.gif');

        if (empty($dimention[0])) {
            $width = 88;
        } else {
            $width = ($dimention[0] > 144) ? 144 : $dimention[0];
        }

        if (empty($dimention[1])) {
            $height = 31;
        } else {
            $height = ($dimention[1] > 400) ? 400 : $dimention[1];
        }

        $tpl->assign('image_width', $width);

        $tpl->assign('image_height', $height);

        foreach ($topics as $value) {
            $tpl->append('items', ['title' => xoops_convert_encoding($value['topic']), 'link' => htmlspecialchars($value['topiclink'], ENT_QUOTES | ENT_HTML5), 'guid' => htmlspecialchars($value['topiclink'], ENT_QUOTES | ENT_HTML5), 'pubdate' => xoops_convert_encoding($value['date'])]);
        }
    }
}
$tpl->display('db:komments_rss.html');
