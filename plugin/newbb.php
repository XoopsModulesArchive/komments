<?php

function b_komments_newbb($maxtopic, $module_mid)
{
    //----------------------------------------------

    // if you want to show Private forum's Comments.

    // please $private_bb = true;

    // if you not want to show praivate forum's Comments.

    // please $private_bb = false;

    $private_bb = true;

    //----------------------------------------------

    // View mode (thread or flat) get from user details

    $userSetting = true;

    //----------------------------------------------

    global $xoopsUser;

    $comment = [];

    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    if (true === $private_bb && $xoopsUser) {
        $uid = $xoopsUser->uid();

        $pu_SQL = 'SELECT forum_id FROM ' . $xoopsDB->prefix('bb_forum_access') . ' WHERE user_id=' . $uid;

        if (!$pu_query = $xoopsDB->query($pu_SQL)) {
            echo 'Error! (newbb)';
        }

        while (false !== ($pu_result = $xoopsDB->fetchArray($pu_query))) {
            $forum[] = $pu_result['forum_id'];
        }
    }

    if (0 == !count($forum)) {
        $SQL = 'SELECT t.topic_id, t.topic_title, t.topic_views, t.topic_replies, t.forum_id, f.forum_name, p.post_time, p.uid FROM ('
               . $xoopsDB->prefix('bb_topics')
               . ' t LEFT JOIN '
               . $xoopsDB->prefix('bb_forums')
               . ' f ON t.forum_id=f.forum_id) LEFT JOIN '
               . $xoopsDB->prefix('bb_posts')
               . ' p ON t.topic_last_post_id=p.post_id '
               . 'WHERE (f.forum_type <> 1) OR (f.forum_id IN ('
               . implode(', ', $forum)
               . ') AND t.forum_id IN ('
               . implode(', ', $forum)
               . ') AND f.forum_type=1) '
               . 'ORDER BY t.topic_time DESC';
    } else {
        $SQL = 'SELECT t.topic_id, t.topic_title, t.topic_views, t.topic_replies, t.forum_id, f.forum_name, p.post_time, p.uid FROM ('
               . $xoopsDB->prefix('bb_topics')
               . ' t LEFT JOIN '
               . $xoopsDB->prefix('bb_forums')
               . ' f ON t.forum_id=f.forum_id) LEFT JOIN '
               . $xoopsDB->prefix('bb_posts')
               . ' p ON t.topic_last_post_id=p.post_id '
               . 'WHERE (f.forum_type <> 1) '
               . 'ORDER BY t.topic_time DESC';
    }

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (newbb)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['post_time']);

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/newbb.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/newbb/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/newbb/viewforum.php?forum=' . $result['forum_id'];

        $comment[$key]['place'] = $result['forum_name'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/newbb/viewtopic.php?forum=' . $result['forum_id'] . '&topic_id=' . $result['topic_id'];

        $comment[$key]['topic'] = $result['topic_title'];

        $comment[$key]['user'] = $result['uid'];

        $comment[$key]['read'] = $result['topic_views'];

        $comment[$key]['reply'] = $result['topic_replies'];

        $comment[$key]['date'] = $result['post_time'];
    }

    return $comment;
}
