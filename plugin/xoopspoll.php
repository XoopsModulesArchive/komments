<?php

function b_komments_xoopspoll($maxtopic, $module_mid)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $comment = [];

    $oncom[] = 0;

    $SQL = 'SELECT count(*) AS reply, max(com.com_id) as comid, p.poll_id, p.question, p.user_id, p.votes, max(com.com_created) AS time FROM '
           . $xoopsDB->prefix('xoopspoll_desc')
           . ' p LEFT JOIN '
           . $xoopsDB->prefix('xoopscomments')
           . ' com ON p.poll_id=com.com_itemid '
           . 'WHERE com.com_status=2 AND com.com_modid='
           . $module_mid
           . ' '
           . 'GROUP BY com.com_itemid '
           . 'ORDER BY com.com_created DESC';

    if (!$query = $xoopsDB->query($SQL, 10, 0)) {
        echo 'Error! (Poll)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $userSQL = 'SELECT com_uid FROM ' . $xoopsDB->prefix('xoopscomments') . ' WHERE com_id=' . $result['comid'];

        if (!$userQuery = $xoopsDB->query($userSQL, 1, 0)) {
            echo 'Error! (Poll_comments_user)';
        }

        $userData = $xoopsDB->fetchArray($userQuery);

        $p_SQL = 'SELECT max(time) as polltime FROM ' . $xoopsDB->prefix('xoopspoll_log') . ' WHERE poll_id=' . $result['poll_id'];

        if (!$log_Query = $xoopsDB->query($p_SQL, 1, 0)) {
            echo 'Error! (Poll_log)';
        }

        $log_result = $xoopsDB->fetchArray($log_Query);

        $key = komments_KeyCheck($comment, max($result['time'], $log_result['polltime']));

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/xoopspoll.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/xoopspoll/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/xoopspoll/index.php';

        $comment[$key]['place'] = 'Poll';

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/xoopspoll/pollresults.php?poll_id=' . $result['poll_id'];

        $comment[$key]['topic'] = $result['question'];

        $comment[$key]['user'] = $userData['com_uid'];

        $comment[$key]['reply'] = $result['reply'];

        $comment[$key]['read'] = $result['votes'];

        $comment[$key]['date'] = $key;

        $oncom[] = $result['poll_id'];
    }

    $SQL = 'SELECT poll_id, question, user_id, votes, start_time FROM ' . $xoopsDB->prefix('xoopspoll_desc') . ' WHERE poll_id NOT IN (' . implode(',', $oncom) . ') ' . 'ORDER BY start_time DESC';

    if (!$query = $xoopsDB->query($SQL, 10, 0)) {
        echo 'Error! (Poll)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $p_SQL = 'SELECT max(time) as time FROM ' . $xoopsDB->prefix('xoopspoll_log') . ' WHERE poll_id=' . $result['poll_id'];

        if (!$log_query = $xoopsDB->query($p_SQL, 1, 0)) {
            echo 'Error! (Poll3)';
        }

        $log_result = $xoopsDB->fetchArray($log_query);

        $key = komments_KeyCheck($comment, max($result['start_time'], $log_result['time']));

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/xoopspoll.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/xoopspoll/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/xoopspoll/index.php';

        $comment[$key]['place'] = 'Poll';

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/xoopspoll/pollresults.php?poll_id=' . $result['poll_id'];

        $comment[$key]['topic'] = $result['question'];

        $comment[$key]['user'] = $result['user_id'];

        $comment[$key]['reply'] = 0;

        $comment[$key]['read'] = $result['votes'];

        $comment[$key]['date'] = $key;
    }

    return $comment;
}
