<?php

$modversion['name'] = _MI_komments_NAME;
$modversion['version'] = 0.61;
$modversion['description'] = _MI_komments_DESC;
$modversion['author'] = 'Bob++( bob@ferrari.104.net )';
$modversion['credits'] = 'Bob++';
$modversion['help'] = '';
$modversion['license'] = 'GPL see LICENSE';
$modversion['official'] = 0;
$modversion['image'] = 'images/komments_logo.gif';
$modversion['dirname'] = 'komments';

$modversion['hasAdmin'] = 1;

$modversion['hasMain'] = 1;

// Setting
$modversion['config'][1]['name'] = 'Mode';
$modversion['config'][1]['title'] = '_MI_komments_Conf_Mode';
$modversion['config'][1]['description'] = '_MI_komments_Conf_Mode_D';
$modversion['config'][1]['formtype'] = 'select';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = '1';
$modversion['config'][1]['options'] = ['_MI_komments_Conf_Mode_Mod' => 1, '_MI_komments_Conf_Mode_Mix' => 2];

$modversion['config'][2]['name'] = 'TopicCount';
$modversion['config'][2]['title'] = '_MI_komments_Conf_T-Count';
$modversion['config'][2]['description'] = '_MI_komments_Conf_T-Count_D';
$modversion['config'][2]['formtype'] = 'textbox';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = '5';

$modversion['config'][3]['name'] = 'Template';
$modversion['config'][3]['title'] = '_MI_komments_Conf_Template';
$modversion['config'][3]['description'] = '_MI_komments_Conf_TemplateD';
$modversion['config'][3]['formtype'] = 'select';
$modversion['config'][3]['valuetype'] = 'text';
$modversion['config'][3]['default'] = 'full';
$modversion['config'][3]['options'] = ['_MI_komments_Conf_temp_full' => 'full', '_MI_komments_Conf_temp_med' => 'medium', '_MI_komments_Conf_temp_mini' => 'small'];

$modversion['config'][4]['name'] = 'User';
$modversion['config'][4]['title'] = '_MI_komments_Conf_User';
$modversion['config'][4]['description'] = '_MI_komments_Conf_User_D';
$modversion['config'][4]['formtype'] = 'select';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = '1';
$modversion['config'][4]['options'] = ['_MI_komments_Conf_User_No' => 0, '_MI_komments_Conf_User_Av' => 1, '_MI_komments_Conf_User_Na' => 2];

$modversion['config'][5]['name'] = 'Micon';
$modversion['config'][5]['title'] = '_MI_komments_Conf_micon';
$modversion['config'][5]['description'] = '_MI_komments_Conf_micon_D';
$modversion['config'][5]['formtype'] = 'yesno';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = '1';

$modversion['config'][6]['name'] = 'catmax';
$modversion['config'][6]['title'] = '_MI_komments_Conf_catmax';
$modversion['config'][6]['description'] = '_MI_komments_Conf_catmax_D';
$modversion['config'][6]['formtype'] = 'textbox';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = '0';

$modversion['config'][7]['name'] = 'topicmax';
$modversion['config'][7]['title'] = '_MI_komments_Conf_topicmax';
$modversion['config'][7]['description'] = '_MI_komments_Conf_topicmax_D';
$modversion['config'][7]['formtype'] = 'textbox';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = '60';

$modversion['config'][8]['name'] = 'timestamp';
$modversion['config'][8]['title'] = '_MI_komments_Conf_timestp';
$modversion['config'][8]['description'] = '_MI_komments_Conf_timestp_D';
$modversion['config'][8]['formtype'] = 'textbox';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = 'Y/m/d H:i:s';

$modversion['config'][9]['name'] = 'footer';
$modversion['config'][9]['title'] = '_MI_komments_Conf_footer';
$modversion['config'][9]['description'] = '_MI_komments_Conf_footer_D';
$modversion['config'][9]['formtype'] = 'yesno';
$modversion['config'][9]['valuetype'] = 'int';
$modversion['config'][9]['default'] = '1';

$modversion['config'][10]['name'] = 'footer_row';
$modversion['config'][10]['title'] = '_MI_komments_Conf_footerrow';
$modversion['config'][10]['description'] = '_MI_komments_Conf_footrow_D';
$modversion['config'][10]['formtype'] = 'textbox';
$modversion['config'][10]['valuetype'] = 'int';
$modversion['config'][10]['default'] = '3';

$modversion['config'][11]['name'] = 'rss';
$modversion['config'][11]['title'] = '_MI_komments_Conf_rss';
$modversion['config'][11]['description'] = '_MI_komments_Conf_rss_D';
$modversion['config'][11]['formtype'] = 'yesno';
$modversion['config'][11]['valuetype'] = 'int';
$modversion['config'][11]['default'] = '1';

// Blocks
$modversion['blocks'][1]['file'] = 'komments_Comments.php';
$modversion['blocks'][1]['name'] = _MI_komments_Block_name1;
$modversion['blocks'][1]['description'] = 'Shows last comments';
$modversion['blocks'][1]['show_func'] = 'b_komments_comments_show';
$modversion['blocks'][1]['options'] = '10|komments_Content_full.html|1|1|0|25|Y/m/d H:i:s|1|3';
$modversion['blocks'][1]['edit_func'] = 'b_komments_comments_edit';
$modversion['blocks'][1]['template'] = 'komments_block_Comments.html';

// Content Template
$modversion['templates'][1]['file'] = 'komments_index.html';
$modversion['templates'][1]['description'] = 'Index Content.';

$modversion['templates'][2]['file'] = 'komments_Content_full.html';
$modversion['templates'][2]['description'] = 'Fullsize Content.';

$modversion['templates'][3]['file'] = 'komments_Content_medium.html';
$modversion['templates'][3]['description'] = 'Mediumsize Content.';

$modversion['templates'][4]['file'] = 'komments_Content_small.html';
$modversion['templates'][4]['description'] = 'Smallsize Content.';

$modversion['templates'][5]['file'] = 'komments_footer.html';
$modversion['templates'][5]['description'] = 'Footer Content.';

$modversion['templates'][6]['file'] = 'komments_module.html';
$modversion['templates'][6]['description'] = 'Module Fullsize Content.';

$modversion['templates'][7]['file'] = 'komments_rss.html';
$modversion['templates'][7]['description'] = 'headline Content.';
