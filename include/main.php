<?php

function komments_main($option)
{
    $plugin_dir = XOOPS_ROOT_PATH . '/modules/komments/plugin/';

    $pluginimg_dir = XOOPS_ROOT_PATH . '/modules/komments/plugin/image/';

    $pluginimg_http = XOOPS_URL . '/modules/komments/plugin/image/';

    //使用権限のあるモジュールリストを取得する

    $mod_lists = komments_getRightMList();

    foreach ($mod_lists as $mod => $module) {
        $mod_plugin_file = $plugin_dir . $mod . '.php';

        //プラグインを読み込む

        if (file_exists($mod_plugin_file)) {
            require_once $mod_plugin_file;

            if (function_exists('b_komments_' . $mod)) {
                //コメント情報を各モジュールごとに表示件数の数だけ得る。

                $comment[] = call_user_func('b_komments_' . $mod, $option, $module['moduleid']);

                //モジュールに対応した画像が確認されればフッタのイメージおよびモジュール名を設定

                if (file_exists($pluginimg_dir . $mod . '.gif')) {
                    $footer['img'][] = $pluginimg_http . $mod . '.gif';

                    $footer['name'][] = $module['name'];
                } elseif (file_exists($pluginimg_dir . $mod . '.png')) {
                    $footer['img'][] = $pluginimg_http . $mod . '.png';

                    $footer['name'][] = $module['name'];
                }
            }
        }
    }

    $data['topic'] = $comment;

    $data['footer'] = $footer;

    return $data;
}
