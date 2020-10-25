<?php

/**
 * komments v0.5x,0.6x plugin for piCal (0.5x/0.4x
 *
 * @param mixed $maxtopic
 * @return array
 * @return array
 * @version   0.1.2sp1
 *
 * @author    Bob++(bobpp@finaliga.com)+GIJOE
 * @copyright copyright (c) 2005 project-bop.
 */

// このプラグインは 終了したイベントについては表示しません。
function b_komments_piCal($maxtopic)
{
    // GIJ added block start

    global $xoopsUser;

    if (!is_object($xoopsUser)) {
        // 閲覧者がゲストなら公開(PUBLIC)レコードのみ

        $whr_class = "class='PUBLIC'";
    } elseif ($xoopsUser->isadmin()) {
        // 閲覧者が管理者なら常にTrue

        $whr_class = '1';
    } else {
        // 通常ユーザなら、PUBLICレコードか、ユーザIDが一致するレコード、または、所属しているグループIDのうちの一つがレコードのグループIDと一致するレコード

        $gids = $xoopsUser->getGroups();

        $uid = $xoopsUser->uid();

        $ids = '';

        // var_dump( $xoopsUser->getGroups() ) ;

        foreach ($gids as $gid) {
            $ids .= "$gid,";
        }

        $ids = mb_substr($ids, 0, -1);

        if (0 == (int)$ids) {
            $group_section = '';
        } else {
            $group_section = "OR groupid IN ($ids)";
        }

        $whr_class = "(class='PUBLIC' OR uid=$uid $group_section)";

        // var_dump( $whr_class ) ;
    }

    // GIJ added block end

    $comment = [];

    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $SQL = 'SELECT id, uid, summary, UNIX_TIMESTAMP(dtstamp) as dtstamp FROM ' . $xoopsDB->prefix('pical_event') . ' WHERE end NOT BETWEEN 0 and ' . time() . ' AND (rrule_pid=0 OR rrule_pid=id) ' . " AND admission<>0 AND $whr_class " . // GIJ added line
               'ORDER by dtstamp DESC';

    if (!$query = $xoopsDB->query($SQL, $maxtopic, 0)) {
        echo 'Error! (piCal)';
    }

    while (false !== ($result = $xoopsDB->fetchArray($query))) {
        $key = komments_KeyCheck($comment, $result['dtstamp']);

        $id = (int)$result['id'];

        $comment[$key]['moduleimg'] = XOOPS_URL . '/modules/komments/plugin/image/piCal.gif';

        $comment[$key]['modulelink'] = XOOPS_URL . '/modules/piCal/index.php';

        $comment[$key]['placelink'] = XOOPS_URL . '/modules/piCal/index.php';

        $comment[$key]['place'] = 'piCal';

        $comment[$key]['topiclink'] = XOOPS_URL . '/modules/piCal/index.php?action=View&caldate=' . $result['dtstamp'] . '&event_id=' . $id;

        $comment[$key]['topic'] = $result['summary'];

        $comment[$key]['user'] = $result['uid'];

        $comment[$key]['read'] = 0;

        $comment[$key]['reply'] = 0;

        $comment[$key]['date'] = $result['dtstamp'];
    }

    return $comment;
}
