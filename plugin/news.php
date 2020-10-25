<?php

function b_komments_news($maxtopic, $module_mid)
{
    global $xoopsUser;

    $comment = [];

    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $SQL = 'SELECT s.storyid, s.uid, s.title, s.created, s.counter, t.topic_id, t.topic_title FROM '
               . $xoopsDB->prefix('stories')
               . ' s left join '
               . $xoopsDB->prefix('topics')
               . ' t on s.topicid=t.topic_id '
               . 'WHERE s.expired NOT BETWEEN 1 AND '
               . time()
               . ' AND s.published BETWEEN 1 AND '
               . time()
               . ' AND s.comments=0 '
               . 'ORDER BY s.created DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (news)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['created']);

        $comment[$key]['reply'] = 0;

        $comment[$key]['user'] = $result['uid'];

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/news.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/news/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/news/index.php?storytopic=' . $result['topic_id'];

        $comment[$key]['place'] = $result['topic_title'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/news/article.php?storyid=' . $result['storyid'];

        $comment[$key]['topic'] = $result['title'];

        $comment[$key]['read'] = $result['counter'];

        $comment[$key]['date'] = $key;
    }

    $c_SQL = 'SELECT count(*) as reply, max(c.com_id) AS comid, s.storyid, s.title, s.created, s.counter, t.topic_id, t.topic_title, max(c.com_created) as time FROM ('
             . $xoopsDB->prefix('stories')
             . ' s  LEFT JOIN '
             . $xoopsDB->prefix('topics')
             . ' t ON s.topicid=t.topic_id) LEFT JOIN '
             . $xoopsDB->prefix('xoopscomments')
             . ' c ON c.com_itemid=s.storyid '
             . 'WHERE c.com_status=2 AND s.expired NOT BETWEEN 1 AND '
             . time()
             . ' AND s.published BETWEEN 1 AND '
             . time()
             . ' AND c.com_modid='
             . $module_mid
             . ' '
             . 'GROUP BY c.com_itemid '
             . 'ORDER BY c.com_created DESC';

    if (!$query2 = $xoopsDB->query($c_SQL, $maxtopic, 0)) {
        echo 'Error! (news_comments)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query2))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (news_comments_user)';
        }

        $comment_data = $xoopsDB->fetchArray($userQuery);

        $key = komments_KeyCheck($comment, $result['time']);

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['user'] = $comment_data['com_uid'];

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/news.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/news/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/news/index.php?storytopic=' . $result['topic_id'];

        $comment[$key]['place'] = $result['topic_title'];

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/news/article.php?storyid=' . $result['storyid'];

        $comment[$key]['topic'] = $result['title'];

        $comment[$key]['read'] = $result['counter'];

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
