<?php

function b_komments_bwiki($maxtopic)
{
    global $xoopsConfig;

    $comment = [];

    $file = @file(XOOPS_ROOT_PATH . '/modules/bwiki/cache/recent.dat');

    $recent = array_slice($file, 0, $maxtopic);

    foreach ($recent as $line) {
        [$time, $page] = explode("\t", trim($line));

        $encpage = rawurlencode($page);

        $key = komments_KeyCheck($comment, $time + ($xoopsConfig['server_TZ']) * 3600);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/bwiki.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/bwiki/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/bwiki/index.php';

        $comment[$key]['place'] = 'B-Wiki';

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/bwiki/index.php?' . $encpage;

        $comment[$key]['topic'] = $page;

        $comment[$key]['read'] = 0;

        $comment[$key]['reply'] = 0;

        $comment[$key]['user'] = 0;

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
