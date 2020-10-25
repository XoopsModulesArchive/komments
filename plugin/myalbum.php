<?php

function b_komments_myalbum($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    $c_SQL = 'SELECT count(*) as reply, max(com.com_id) as comid, p.lid, p.title as ptitle, p.hits, cat.cid as catid, cat.title as cattitle, max(com.com_created) as time FROM ('
             . $xoopsDB->prefix('myalbum_photos')
             . ' p LEFT JOIN '
             . $xoopsDB->prefix('myalbum_cat')
             . ' cat ON p.cid=cat.cid) LEFT JOIN '
             . $xoopsDB->prefix('xoopscomments')
             . ' com ON com.com_itemid=p.lid '
             . 'WHERE p.status=1 AND com.com_status=2 AND com.com_modid='
             . $module_mid
             . ' '
             . 'GROUP BY com.com_itemid '
             . 'ORDER BY com.com_created DESC';

    if (!$query2 = $xoopsDB->query($c_SQL, $maxtopic, 0)) {
        echo 'Error! (myalbum_Comment)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query2))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (myalbum_comments_user)';
        }

        $userData = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, $result['time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/myalbum.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/myalbum/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/myalbum/viewcat.php?cid=' . $result['catid'];

        $comment[$key]['place'] = $result['cattitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/myalbum/photo.php?lid=' . $result['lid'];

        $comment[$key]['topic'] = $result['ptitle'];

        $comment[$key]['user'] = $userData['com_uid'];

        $comment[$key]['read'] = $result['hits'];

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['lid'];
    }

    $SQL = 'SELECT p.lid, p.submitter, p.title as ptitle, p.date, p.hits, cat.cid as catid, cat.title as cattitle FROM '
           . $xoopsDB->prefix('myalbum_photos')
           . ' p LEFT JOIN '
           . $xoopsDB->prefix('myalbum_cat')
           . ' cat ON p.cid=cat.cid '
           . 'WHERE p.status=1 AND p.lid NOT IN ('
           . implode(',', $oncom)
           . ') '
           . 'ORDER BY p.date DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (myalbum)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['date']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/myalbum.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/myalbum/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/myalbum/viewcat.php?cid=' . $result['catid'];

        $comment[$key]['place'] = $result['cattitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/myalbum/photo.php?lid=' . $result['lid'];

        $comment[$key]['topic'] = $result['ptitle'];

        $comment[$key]['user'] = $result['submitter'];

        $comment[$key]['read'] = $result['hits'];

        $comment[$key]['reply'] = 0;

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
