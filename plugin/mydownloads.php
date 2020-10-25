<?php

function b_komments_mydownloads($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    $c_SQL = 'SELECT count(*) as reply, max(com.com_id) AS comid, d.lid, d.cid, d.date, d.title as dtitle, d.submitter, d.date,d.hits,d.votes,c.title as ctitle, max(com.com_created) as time FROM ('
             . $xoopsDB->prefix('mydownloads_downloads')
             . ' d  LEFT JOIN '
             . $xoopsDB->prefix('mydownloads_cat')
             . ' c ON d.cid=c.cid) LEFT JOIN '
             . $xoopsDB->prefix('xoopscomments')
             . ' com ON com.com_itemid=d.lid '
             . 'WHERE com.com_status=2 AND com.com_modid='
             . $module_mid
             . ' AND d.status>0 AND d.comments>0 '
             . 'GROUP BY com.com_itemid '
             . 'ORDER BY com.com_created DESC';

    if (!$query2 = $xoopsDB->query($c_SQL, $maxtopic, 0)) {
        echo 'Error! (mydownloads_comments)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query2))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (mydownloads_comments_user)';
        }

        $comment_data = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, max($result['date'], $result['time']));

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/mydownloads.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/mydownloads/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/mydownloads/viewcat.php?cid=' . $result['cid'];

        $comment[$key]['place'] = $result['ctitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/mydownloads/singlefile.php?lid=' . $result['lid'];

        $comment[$key]['topic'] = $result['dtitle'];

        $comment[$key]['user'] = $comment_data['com_uid'];

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['read'] = $result['hits'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['lid'];
    }

    $SQL = 'SELECT d.lid, d.cid, d.title as dtitle, d.submitter, d.date, d.hits, d.votes, c.title as ctitle FROM '
           . $xoopsDB->prefix('mydownloads_downloads')
           . ' d LEFT JOIN '
           . $xoopsDB->prefix('mydownloads_cat')
           . ' c ON d.cid=c.cid '
           . 'WHERE d.lid NOT IN ('
           . implode(',', $oncom)
           . ') AND d.status>0 AND d.comments=0 '
           . 'ORDER BY d.date DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (mydownloads)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['date']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/mydownloads.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/mydownloads/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/mydownloads/viewcat.php?cid=' . $result['cid'];

        $comment[$key]['place'] = $result['ctitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/mydownloads/singlefile.php?lid=' . $result['lid'];

        $comment[$key]['topic'] = $result['dtitle'];

        $comment[$key]['read'] = $result['hits'];

        $comment[$key]['reply'] = 0;

        $comment[$key]['user'] = $result['submitter'];

        $comment[$key]['date'] = $result['date'];
    }

    return $comment;
}
