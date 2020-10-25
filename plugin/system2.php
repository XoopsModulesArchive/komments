<?php

function b_komments_system($maxtopic)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $SQL = 'SELECT uid, uname, user_regdate, posts FROM ' . $xoopsDB->prefix('users') . ' WHERE level<>0 ' . 'ORDER BY user_regdate DESC';

    if (!$userQ = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (system_user)';
    }

    while (false !== ($userData = $xoopsDB->fetchArray($userQ))) {
        $key = komments_KeyCheck($comment, $userData['user_regdate']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/system.gif';

        $comment[$key]['modulelink'] = XOOPS_URL;

        $comment[$key]['placelink'] = XOOPS_URL;

        $comment[$key]['place'] = 'New User';

        $comment[$key]['topiclink'] = XOOPS_URL . '/userinfo.php?uid=' . $userData['uid'];

        $comment[$key]['topic'] = $userData['uname'];

        $comment[$key]['user'] = $userData['uid'];

        $comment[$key]['read'] = 0;

        $comment[$key]['reply'] = $userData['posts'];

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
