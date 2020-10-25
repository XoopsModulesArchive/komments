<?php

// $Id: wfsection.php,v 1.1 2006/03/27 09:25:08 mikhail Exp $
function b_komments_wfsection($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    global $xoopsUser;

    $groupid[] = 0;

    if ($xoopsUser) {
        $userid = $xoopsUser->uid();

        $SQL = 'SELECT groupid FROM ' . $xoopsDB->prefix('groups_users_link') . ' WHERE uid=' . $userid;

        $query = $xoopsDB->query($SQL);

        while (false !== ($result = $xoopsDB->fetchArray($query))) {
            $temp = explode(',', $result['groupid']);

            foreach ($temp as $value) {
                $groupid[] = $value;
            }
        }
    } else {
        $groupid[] = XOOPS_GROUP_ANONYMOUS;
    }

    $c_SQL = 'SELECT count(*) as reply, max(com.com_id) as comid, a.articleid, a.uid, a.title as atitle, a.counter, cat.id as catid, cat.title as cattitle, max(com.com_created) as time FROM ('
             . $xoopsDB->prefix('wfs_article')
             . ' a LEFT JOIN '
             . $xoopsDB->prefix('wfs_category')
             . ' cat ON a.categoryid=cat.id) LEFT JOIN '
             . $xoopsDB->prefix('xoopscomments')
             . ' com ON com.com_itemid=a.articleid '
             . "WHERE a.offline=0 AND a.groupid REGEXP '["
             . implode('', $groupid)
             . "]' AND com.com_status=2 AND com.com_modid="
             . $module_mid
             . ' AND a.expired NOT BETWEEN 1 AND '
             . time()
             . ' AND a.published<'
             . time()
             . ' '
             . 'GROUP BY com.com_itemid '
             . 'ORDER BY com.com_created DESC';

    if (!$query2 = $xoopsDB->query($c_SQL, $maxtopic, 0)) {
        echo 'Error! (wfsection_Comment)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query2))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (wfsection_comments_user)';
        }

        $userData = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, $result['time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/wfsection.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/wfsection/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/wfsection/index.php?category=' . $result['catid'];

        $comment[$key]['place'] = $result['cattitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/wfsection/article.php?articleid=' . $result['articleid'];

        $comment[$key]['topic'] = $result['atitle'];

        $comment[$key]['user'] = $userData['com_uid'];

        $comment[$key]['read'] = $result['counter'];

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['articleid'];
    }

    $SQL = 'SELECT a.articleid, a.uid, a.title as atitle, a.created, a.counter, cat.id as catid, cat.title as cattitle FROM '
           . $xoopsDB->prefix('wfs_article')
           . ' a LEFT JOIN '
           . $xoopsDB->prefix('wfs_category')
           . ' cat ON a.categoryid=cat.id '
           . "WHERE a.offline=0 AND a.groupid REGEXP '["
           . implode('', $groupid)
           . "]' AND a.articleid NOT IN ("
           . implode(',', $oncom)
           . ') AND a.expired NOT BETWEEN 1 AND '
           . time()
           . ' AND a.published<'
           . time()
           . ' '
           . 'ORDER BY a.created DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (wfsection)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['created']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/wfsection.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/wfsection/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/wfsection/index.php?category=' . $result['catid'];

        $comment[$key]['place'] = $result['cattitle'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/wfsection/article.php?articleid=' . $result['articleid'];

        $comment[$key]['topic'] = $result['atitle'];

        $comment[$key]['user'] = $result['uid'];

        $comment[$key]['read'] = $result['counter'];

        $comment[$key]['reply'] = 0;

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
