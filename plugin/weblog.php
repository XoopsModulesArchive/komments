<?php

function b_komments_weblog($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    $c_SQL = 'SELECT count(*) as reply, max(com.com_id) as comid, w.blog_id, w.title, w.reads, cat.cat_id, cat.cat_title, max(com.com_created) as time FROM ('
             . $xoopsDB->prefix('weblog')
             . ' w LEFT JOIN '
             . $xoopsDB->prefix('weblog_category')
             . ' cat ON w.cat_id=cat.cat_id) LEFT JOIN '
             . $xoopsDB->prefix('xoopscomments')
             . ' com ON com.com_itemid=w.blog_id '
             . 'WHERE w.private=0 AND com.com_status=2 AND com.com_modid='
             . $module_mid
             . ' '
             . 'GROUP BY com.com_itemid '
             . 'ORDER BY com.com_created DESC';

    if (!$query2 = $xoopsDB->query($c_SQL, $maxtopic, 0)) {
        echo 'Error! (weblog_Comment)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query2))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (weblog_comments_user)';
        }

        $userData = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, $result['time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/weblog.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/weblog/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/weblog/index.php?cat_id=' . $result['cat_id'];

        $comment[$key]['place'] = $result['cat_title'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/weblog/details.php?blog_id=' . $result['blog_id'];

        $comment[$key]['topic'] = $result['title'];

        $comment[$key]['user'] = $userData['com_uid'];

        $comment[$key]['read'] = $result['reads'];

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['blog_id'];
    }

    $SQL = 'SELECT w.blog_id, w.user_id, w.title, w.created, w.reads, cat.cat_id, cat.cat_title FROM '
           . $xoopsDB->prefix('weblog')
           . ' w LEFT JOIN '
           . $xoopsDB->prefix('weblog_category')
           . ' cat ON w.cat_id=cat.cat_id '
           . 'WHERE w.private=0 AND w.blog_id NOT IN ('
           . implode(',', $oncom)
           . ') '
           . 'ORDER BY w.created DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (weblog)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['created']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/weblog.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/weblog/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/weblog/index.php?cat_id=' . $result['cat_id'];

        $comment[$key]['place'] = $result['cat_title'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/weblog/details.php?blog_id=' . $result['blog_id'];

        $comment[$key]['topic'] = $result['title'];

        $comment[$key]['user'] = $result['user_id'];

        $comment[$key]['read'] = $result['reads'];

        $comment[$key]['reply'] = 0;

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
