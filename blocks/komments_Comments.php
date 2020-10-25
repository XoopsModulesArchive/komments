<?php

function b_komments_comments_show($options)
{
    require XOOPS_ROOT_PATH . '/modules/komments/header.php';

    //テーブル表題の言語情報を取得する

    $header['place'] = _MB_komments_place;

    $header['topic'] = _MB_komments_topic;

    $header['poster'] = _MB_komments_poster;

    $header['reply'] = _MB_komments_reply;

    $header['read'] = _MB_komments_read;

    $header['date'] = _MB_komments_date;

    // オプション情報を取得する

    $option['micon'] = $options[2];

    $option['user'] = $options[3];

    $option['footer'] = $options[7];

    $data = komments_main($options[0]);

    $topics = komments_arrayMarge($data['topic']);

    $topic = komments_finalize($topics, $options[0], $options[3], $options[4], $options[5], $options[6]);

    $footer = $data['footer'];

    $footer['option']['row'] = $options[8];

    $template = new XoopsTpl();

    $template->assign('option', $option);

    $template->assign('header', $header);

    $template->assign('topics', $topic);

    $template->assign('footer', $footer);

    $block = $template->fetch('db:' . $options[1]);

    return $block;
}

function b_komments_comments_edit($options)
{
    // 表示件数

    $inputtag = "<input type='text' name='options[0]' value='" . $options[0] . "'>";

    $form = sprintf(_MB_komments_DISPLAY, $inputtag);

    $form .= '<br>';

    // フルサイズ表示

    $select = "<Select name='options[1]'>";

    if ('komments_Content_full.html' == $options[1]) {
        $select .= "<option value='komments_Content_full.html' SELECTED>" . _MB_komments_Full_use . '</option>';

        $select .= "<option value='komments_Content_medium.html'>" . _MB_komments_Medium . '</option>';

        $select .= "<option value='komments_Content_small.html'>" . _MB_komments_Small . '</option>';
    } elseif ('komments_Content_medium.html' == $options[1]) {
        $select .= "<option value='komments_Content_full.html'>" . _MB_komments_Full_use . '</option>';

        $select .= "<option value='komments_Content_medium.html' SELECTED>" . _MB_komments_Medium . '</option>';

        $select .= "<option value='komments_Content_small.html'>" . _MB_komments_Small . '</option>';
    } else {
        $select .= "<option value='komments_Content_full.html'>" . _MB_komments_Full_use . '</option>';

        $select .= "<option value='komments_Content_medium.html'>" . _MB_komments_Medium . '</option>';

        $select .= "<option value='komments_Content_small.html' SELECTED>" . _MB_komments_Small . '</option>';
    }

    $select .= '</select>';

    $form .= sprintf(_MB_komments_Template, $select);

    $form .= '<br>';

    // モジュールアイコン表示

    $select = "<Select name='options[2]'>";

    if (1 == $options[2]) {
        $select .= "<option value='0'>" . _MB_komments_off . '</option>';

        $select .= "<option value='1' SELECTED>" . _MB_komments_on_use . '</option>';
    } else {
        $select .= "<option value='0' SELECTED>" . _MB_komments_off_use . '</option>';

        $select .= "<option value='1'>" . _MB_komments_on . '</option>';
    }

    $select .= '</select>';

    $form .= sprintf(_MB_komments_moduleIcon, $select);

    $form .= '<br>';

    // ユーザ情報表示

    $select = "<Select name='options[3]'>";

    if (1 == $options[3]) {
        $select .= "<option value='0'>" . _MB_komments_off . '</option>';

        $select .= "<option value='1' SELECTED>" . _MB_komments_user_avatar_use . '</option>';

        $select .= "<option value='2'>" . _MB_komments_user_name . '</option>';
    } elseif (2 == $options[3]) {
        $select .= "<option value='0'>" . _MB_komments_off_use . '</option>';

        $select .= "<option value='1'>" . _MB_komments_user_avatar . '</option>';

        $select .= "<option value='2' SELECTED>" . _MB_komments_user_name_use . '</option>';
    } else {
        $select .= "<option value='0' SELECTED>" . _MB_komments_off_use . '</option>';

        $select .= "<option value='1'>" . _MB_komments_user_avatar . '</option>';

        $select .= "<option value='2'>" . _MB_komments_user_name . '</option>';
    }

    $select .= '</select>';

    $form .= sprintf(_MB_komments_Userinfo, $select);

    $form .= '<br>';

    // カテゴリ側表示文字数

    $inputtag = "<input type='text' name='options[4]' value='" . $options[4] . "'>";

    $form .= sprintf(_MB_komments_max_category, $inputtag);

    $form .= '<br>';

    // トピック側表示文字数

    $inputtag = "<input type='text' name='options[5]' value='" . $options[5] . "'>";

    $form .= sprintf(_MB_komments_max_topic, $inputtag);

    $form .= '<br>';

    // タイムスタンプ設定変更

    $inputtag = "<input type='text' name='options[6]' value='" . $options[6] . "'>";

    $form .= sprintf(_MB_komments_timestamp, $inputtag);

    $form .= '<br>';

    // フッタ表示

    $select = "<Select name='options[7]'>";

    if (1 == $options[7]) {
        $select .= "<option value='0'>" . _MB_komments_off . '</option>';

        $select .= "<option value='1' SELECTED>" . _MB_komments_on_use . '</option>';
    } else {
        $select .= "<option value='0' SELECTED>" . _MB_komments_off_use . '</option>';

        $select .= "<option value='1'>" . _MB_komments_on . '</option>';
    }

    $select .= '</select>';

    $form .= sprintf(_MB_komments_footer, $select);

    $form .= '<br>';

    // カテゴリ側表示文字数

    $inputtag = "<input type='text' name='options[8]' value='" . $options[8] . "'>";

    $form .= sprintf(_MB_komments_footer_row, $inputtag);

    $form .= '<br>';

    return $form;
}
