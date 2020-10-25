<?php

// 使用権限のあるモジュールリストを取得する 返り値は配列変数で['moduledir'] => moduleid
function komments_getRightMList()
{
    global $xoopsDB, $xoopsUser;

    $modulepermHandler = xoops_getHandler('groupperm');

    $sql = 'SELECT mid, dirname, name FROM ' . $xoopsDB->prefix('modules') . ' ORDER BY weight ASC';

    $result = $xoopsDB->query($sql);

    while (false !== ($array = $xoopsDB->fetchArray($result))) {
        if ($xoopsUser) {
            if ($modulepermHandler->checkRight('module_read', $array['mid'], $xoopsUser->getGroups())) {
                $modulelist[$array['dirname']]['moduleid'] = $array['mid'];

                $modulelist[$array['dirname']]['name'] = $array['name'];
            }
        } else {
            if ($modulepermHandler->checkRight('module_read', $array['mid'], XOOPS_GROUP_ANONYMOUS)) {
                $modulelist[$array['dirname']]['moduleid'] = $array['mid'];

                $modulelist[$array['dirname']]['name'] = $array['name'];
            }
        }
    }

    return $modulelist;
}

// モジュールごとに配列になっているものを、マージする。（モジュール別で表示しない際は必須
function komments_arrayMarge($array)
{
    $topics = [];

    foreach ($array as $value) {
        foreach ($value as $key => $topicvalue) {
            $setkey = komments_KeyCheck($topics, $key);

            $topics[$setkey] = $topicvalue;
        }
    }

    unset($array);

    return $topics;
}

function komments_minimum($array, $maxtopic)
{
    krsort($array);

    $counter = 0;

    foreach ($array as $key => $value) {
        $counter++;

        $newarray[$key] = $value;

        if ($maxtopic == $counter) {
            break;
        }
    }

    return $newarray;
}

// 最終的に表示件数に丸める。(主にブロック用)
function komments_finalize($array, $maxtopic, $userinfosetting, $catmax, $topicmax, $tsformat)
{
    krsort($array);

    $counter = 0;

    foreach ($array as $key => $value) {
        $counter++;

        $newarray[$key] = $value;

        $newarray[$key]['userprof'] = komments_getUserprof($newarray[$key]['user']);

        $newarray[$key]['user'] = komments_getUserInfo($newarray[$key]['user'], $userinfosetting);

        $newarray[$key]['place'] = komments_strmini($newarray[$key]['place'], $catmax);

        $newarray[$key]['topic'] = komments_strmini($newarray[$key]['topic'], $topicmax);

        $newarray[$key]['date'] = formatTimestamp($newarray[$key]['date'], $tsformat);

        if ($maxtopic == $counter) {
            break;
        }
    }

    return $newarray;
}

// 日付キーの重複を防ぐ 返り値は使用可能なキー
function komments_KeyCheck($array, $key)
{
    $keys = array_keys($array);

    $newkey = $key;

    while (in_array($newkey, $keys, true)) {
        $newkey++;
    }

    return $newkey;
}

// アバターのURL又はユーザ名を返す anonymousの場合はBlank.gifのURLを返します
// $userid = ユーザID (=0はanonymous。
// $option = オプション指定(=1はアバター, 2はユーザ名。2のときのみ文字数丸めを行います
function komments_getUserInfo($userid, $option)
{
    if (1 == $option) {
        if (0 == !$userid) {
            $postuser = new XoopsUser($userid);

            $userinfo = XOOPS_URL . '/uploads/' . $postuser->user_avatar();
        } else {
            $userinfo = XOOPS_URL . '/uploads/blank.gif';
        }
    } elseif (2 == $option) {
        $userinfo = XoopsUser::getUnameFromId($userid);
    }

    return $userinfo;
}

// ユーザプロフィールページへのURLを返す
function komments_getUserprof($userid)
{
    if (0 == !$userid) {
        $profurl = XOOPS_URL . '/userinfo.php?uid=' . $userid;
    } else {
        $profurl = $_PHP['SELF'] . '#';
    }

    return $profurl;
}

// 文字を指定文字数( $stringlong )に丸める。
function komments_strmini($string, $maxlong)
{
    if (0 == !$maxlong && komments_jstrlen($string) > $maxlong) {
        return komments_jsubstr($string, 0, $maxlong) . '...';
    }

    return $string;
}

/*************************************************************************
 * ________________________________
 *
 * jcode.phps by TOMO
 * ________________________________
 *
 *
 * [Version] : 1.34 (2002/10/10)
 * [URL]     : http://www.spencernetwork.org/
 * [E-MAIL]  : groove@spencernetwork.org
 * [Changes] :
 * v1.30 Changed XXXtoUTF8 and UTF8toXXX with conversion tables.
 * v1.31 Deleted a useless and harmful line in JIStoUTF8() (^^;
 * v1.32 Fixed miss type of jsubstr().
 * Fixed HANtoZEN_EUC(), HANtoZEN_SJIS() and HANtoZEN_JIS().
 * v1.33 Fixed JIStoXXX(), HANtoZEN_JIS() and ZENtoHAN_JIS().
 * Added jstr_preg_split() as O-MA-KE No.4.
 * Added jstrcut() as O-MA-KE No.5.
 * Changed the logic of AutoDetect()
 * v1.34 Fixed ZENtoHAN_SJIS()
 * jcode.phps is free but without any warranty.
 * use this script at your own risk.
 **************************************************************************
 * @param     $str
 * @param int $start
 * @param int $length
 * @return string
 */

/*
    O-MA-KE No.1
    jsubstr() - substr() function for japanese(euc-jp)
    for using shift_jis encoding, remove comment string.
*/
function komments_jsubstr($str, $start = 0, $length = 0)
{
    $b = unpack('C*', $str);

    $m = count($b);

    for ($i = 1; $i <= $m; ++$i) {
        if ($b[$i] >= 0x80) {  //Japanese
            //			if ( 0xA0 < $b[$i] && $b[$i] < 0xE0 ) {  //SJIS Hankaku

            //				$jstr[] = chr($b[$i]);

            //			} else {

            $jstr[] = chr($b[$i]) . chr($b[++$i]);

        //			}
        } else {  //ASCII
            $jstr[] = chr($b[$i]);
        }
    }

    if (!isset($jstr)) {
        $jstr[] = '';
    }

    $n = count($jstr);

    if ($start < 0) {
        $start += $n;
    }

    if ($length < 0) {
        $end = $n + $length;
    } else {
        $end = $start + $length;
    }

    if ($end > $n) {
        $end = $n;
    }

    $s = '';

    for ($j = $start; $j < $end; ++$j) {
        $s .= $jstr[$j];
    }

    return $s;
}

/*
    O-MA-KE No.2
    jstrlen() - strlen() function for japanese(euc-jp)
    for using shift_jis encoding, remove comment string.
*/
function komments_jstrlen($str)
{
    $b = unpack('C*', $str);

    $n = count($b);

    $l = 0;

    for ($i = 1; $i <= $n; ++$i) {
        if ($b[$i] >= 0x80//			&& ($b[$i] <= 0xA0 || $b[$i] >= 0xE0)  //exclude SJIS Hankaku
        ) {
            ++$i;
        }

        ++$l;
    }

    return $l;
}
