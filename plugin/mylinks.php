<?php

function b_komments_mylinks($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    $c_SQL = 'SELECT count(*) as reply, max(com.com_id) AS comid, l.lid,l.cid,l.date,l.title as ltitle, l.submitter, l.date,l.hits,l.votes,c.title as ctitle, max(com.com_created) as time FROM ('
             . $xoopsDB->prefix('mylinks_links')
             . ' l  LEFT JOIN '
             . $xoopsDB->prefix('mylinks_cat')
             . ' c ON l.cid=c.cid) LEFT JOIN '
             . $xoopsDB->prefix('xoopscomments')
             . ' com ON com.com_itemid=l.lid '
             . 'WHERE com.com_status=2 AND com.com_modid='
             . $module_mid
             . ' AND l.status>0 AND l.comments>0 '
             . 'GROUP BY com.com_itemid '
             . 'ORDER BY com.com_created DESC';

    if (!$query2 = $xoopsDB->query($c_SQL, $maxtopic, 0)) {
        echo 'Error! (mylinks_comments)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query2))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (mylinks_comments_user)';
        }

        $comment_data = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, $result['time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/mylinks.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/mylinks/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/mylinks/viewcat.php?cid=' . $result['cid'];

        $comment[$key]['place'] = $result['ctitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/mylinks/singlelink.php?lid=' . $result['lid'];

        $comment[$key]['topic'] = $result['ltitle'];

        $comment[$key]['user'] = $comment_data['com_uid'];

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['read'] = $result['hits'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['lid'];
    }

    $SQL = 'SELECT l.lid,l.cid,l.title as ltitle, l.submitter, l.date,l.hits,l.votes,c.title as ctitle FROM '
           . $xoopsDB->prefix('mylinks_links')
           . ' l LEFT JOIN '
           . $xoopsDB->prefix('mylinks_cat')
           . ' c ON l.cid=c.cid '
           . 'WHERE l.lid NOT IN ('
           . implode(',', $oncom)
           . ') AND l.status>0 AND l.comments=0 '
           . 'ORDER BY l.date DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (mylinks)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['date']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/mylinks.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/mylinks/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/mylinks/viewcat.php?cid=' . $result['cid'];

        $comment[$key]['place'] = $result['ctitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/mylinks/singlelink.php?lid=' . $result['lid'];

        $comment[$key]['topic'] = $result['ltitle'];

        $comment[$key]['user'] = $result['submitter'];

        $comment[$key]['reply'] = 0;

        $comment[$key]['read'] = $result['hits'];

        $comment[$key]['date'] = $result['date'];
    }

    return $comment;
}
