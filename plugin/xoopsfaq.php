<?php

function b_komments_xoopsfaq($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    $SQL = 'SELECT count(*) AS reply, max(com.com_id) AS comid, c.contents_id, c.category_id AS catid, c.contents_title, cat.category_id, cat.category_title, max(com.com_created) AS time FROM ('
           . $xoopsDB->prefix('xoopsfaq_contents')
           . ' c LEFT JOIN '
           . $xoopsDB->prefix('xoopsfaq_categories')
           . ' cat ON c.category_id=cat.category_id) LEFT JOIN '
           . $xoopsDB->prefix('xoopscomments')
           . ' com ON c.contents_id=com.com_itemid '
           . 'WHERE com.com_status=2 AND com.com_modid='
           . $module_mid
           . ' AND c.contents_visible=1 '
           . 'GROUP BY com.com_itemid '
           . 'ORDER BY com.com_created DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (xoopsfaq-comment)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (xoopsfaq_comments_user)';
        }

        $userData = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, $result['time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/xoopsfaq.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/xoopsfaq/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/xoopsfaq/index.php?cat_id=' . $result['category_id'];

        $comment[$key]['place'] = $result['category_title'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/xoopsfaq/index.php?cat_id=' . $result['category_id'] . '#q' . $result['contents_id'];

        $comment[$key]['topic'] = $result['contents_title'];

        $comment[$key]['user'] = $userData['com_uid'];

        $comment[$key]['read'] = 0;

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['articleid'];
    }

    $SQL = 'SELECT c.contents_id, c.category_id AS catid, c.contents_title, cat.category_id, c.contents_time, cat.category_title FROM '
           . $xoopsDB->prefix('xoopsfaq_contents')
           . ' c LEFT JOIN '
           . $xoopsDB->prefix('xoopsfaq_categories')
           . ' cat ON c.category_id=cat.category_id '
           . 'WHERE c.contents_id NOT IN ('
           . implode(',', $oncom)
           . ') AND c.contents_visible=1 '
           . 'ORDER BY c.contents_time DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (xoopsfaq)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['contents_time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/xoopsfaq.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/xoopsfaq/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/xoopsfaq/index.php?cat_id=' . $result['category_id'];

        $comment[$key]['place'] = $result['category_title'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/xoopsfaq/index.php?cat_id=' . $result['category_id'] . '#q' . $result['contents_id'];

        $comment[$key]['topic'] = $result['contents_title'];

        $comment[$key]['user'] = 0;

        $comment[$key]['read'] = 0;

        $comment[$key]['reply'] = 0;

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
