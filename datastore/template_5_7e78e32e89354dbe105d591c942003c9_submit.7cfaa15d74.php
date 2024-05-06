<?php

return <<<'VALUE'
"namespace IPS\\Theme;\nclass class_forums_front_submit extends \\IPS\\Theme\\Template\n{\n\tpublic $cache_key = '01e7aa016a7dd2866c5a24a66e21f7b8';\n\tfunction createTopic( $form, $forum, $title ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\n\nCONTENT;\n\nif ( $club = $forum->club() ):\n$return .= <<<CONTENT\n\n\t\nCONTENT;\n\nif ( \\IPS\\Settings::i()->clubs and \\IPS\\Settings::i()->clubs_header == 'full' ):\n$return .= <<<CONTENT\n\n\t\t\nCONTENT;\n\n$return .= \\IPS\\Theme::i()->getTemplate( \"clubs\", \"core\" )->header( $club, $forum );\n$return .= <<<CONTENT\n\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t<div id='elClubContainer'>\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\n\nCONTENT;\n\nif ( !\\IPS\\Request::i()->isAjax() ):\n$return .= \\IPS\\Theme::i()->getTemplate( \"global\", \"core\" )->pageHeader( \\IPS\\Member::loggedIn()->language()->addToStack( $title ) );\nendif;\n$return .= <<<CONTENT\n\n\n{$form}\n\n\nCONTENT;\n\nif ( $club = $forum->club() ):\n$return .= <<<CONTENT\n\n\t<\/div>\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction createTopicForm( $forum, $hasModOptions, $topic, $id, $action, $elements, $hiddenValues, $actionButtons, $uploadField, $class='', $attributes=array(), $sidebar=NULL, $form=NULL, $errorTabs=array() ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\nCONTENT;\n\n$modOptions = array( 'topic_create_state', 'create_topic_locked', 'create_topic_pinned', 'create_topic_hidden', 'create_topic_featured', 'topic_open_time', 'topic_close_time');\n$return .= <<<CONTENT\n\n\n<form accept-charset='utf-8' class=\"ipsForm \nCONTENT;\n$return .= htmlspecialchars( $class, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" action=\"\nCONTENT;\n$return .= htmlspecialchars( $action, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" method=\"post\" \nCONTENT;\n\nif ( $uploadField ):\n$return .= <<<CONTENT\nenctype=\"multipart\/form-data\"\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n data-ipsForm data-ipsFormSubmit>\n\t<input type=\"hidden\" name=\"\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_submitted\" value=\"1\">\n\t\nCONTENT;\n\nforeach ( $hiddenValues as $k => $v ):\n$return .= <<<CONTENT\n\n\t\t<input type=\"hidden\" name=\"\nCONTENT;\n$return .= htmlspecialchars( $k, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" value=\"\nCONTENT;\n$return .= htmlspecialchars( $v, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\">\n\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\nCONTENT;\n\nif ( $uploadField ):\n$return .= <<<CONTENT\n\n\t\t<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"\nCONTENT;\n$return .= htmlspecialchars( $uploadField, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\">\n\t\t<input type=\"hidden\" name=\"plupload\" value=\"\nCONTENT;\n\n$return .= htmlspecialchars( md5( mt_rand() ), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\">\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\n\t\nCONTENT;\n\nif ( $form->error ):\n$return .= <<<CONTENT\n\n\t\t<p class=\"ipsMessage ipsMessage_error\">\nCONTENT;\n$return .= htmlspecialchars( $form->error, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/p>\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\n\t<div class='ipsBox ipsResponsive_pull'>\n\t\t<h2 class='ipsType_sectionTitle ipsType_reset ipsHide'>\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'topic_details', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n<\/h2>\n\t\t\nCONTENT;\n\nif ( \\count( $elements ) > 1 ):\n$return .= <<<CONTENT\n\n\t\t\t\nCONTENT;\n\nif ( !empty( $errorTabs ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t<p class=\"ipsMessage ipsMessage_error ipsJS_show\">\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'tab_error', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n<\/p>\n\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t<div class='ipsTabs ipsClearfix ipsJS_show' id='tabs_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' data-ipsTabBar data-ipsTabBar-contentArea='#ipsTabs_content_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n'>\n\t\t\t\t<a href='#tabs_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' data-action='expandTabs'><i class='fa fa-caret-down'><\/i><\/a>\n\t\t\t\t<ul role='tablist'>\n\t\t\t\t\t\nCONTENT;\n\nforeach ( $elements as $name => $content ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t<a href='#ipsTabs_tabs_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_tab_\nCONTENT;\n$return .= htmlspecialchars( $name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_panel' id='\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_tab_\nCONTENT;\n$return .= htmlspecialchars( $name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' class=\"ipsTabs_item \nCONTENT;\n\nif ( \\in_array( $name, $errorTabs ) ):\n$return .= <<<CONTENT\nipsTabs_error\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\" role=\"tab\" aria-selected=\"\nCONTENT;\n\nif ( $name == 'mainTab' ):\n$return .= <<<CONTENT\ntrue\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\nfalse\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\">\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nif ( \\in_array( $name, $errorTabs ) ):\n$return .= <<<CONTENT\n<i class=\"fa fa-exclamation-circle\"><\/i> \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\nCONTENT;\n\n$val = \"{$name}\"; $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( $val, ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<\/a>\n\t\t\t\t\t\t<\/li>\n\t\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t\t<\/ul>\n\t\t\t<\/div>\n\t\t\t<div id='ipsTabs_content_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' class='ipsTabs_panels'>\n\t\t\t\t\nCONTENT;\n\nforeach ( $elements as $name => $contents ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t<div id='ipsTabs_tabs_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_tab_\nCONTENT;\n$return .= htmlspecialchars( $name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_panel' class=\"ipsTabs_panel ipsPadding\" aria-labelledby=\"\nCONTENT;\n$return .= htmlspecialchars( $id, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_tab_\nCONTENT;\n$return .= htmlspecialchars( $name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" aria-hidden=\"false\">\n\n\t\t\t\t\t\t\nCONTENT;\n\nif ( $hasModOptions && $name == 'topic_mainTab' ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<div class='ipsColumns ipsColumns_collapsePhone'>\n\t\t\t\t\t\t\t\t<div class='ipsColumn ipsColumn_fluid'>\n\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<ul class='ipsForm ipsForm_vertical'>\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nforeach ( $contents as $inputName => $input ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t\nCONTENT;\n\nif ( !\\in_array( $inputName, $modOptions ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t\t{$input}\n\t\t\t\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<\/ul>\n\t\t\t\t\t\t\nCONTENT;\n\nif ( $hasModOptions && $name == 'topic_mainTab' ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t\t\t<div class='ipsColumn ipsColumn_wide'>\n\t\t\t\t\t\t\t\t\t\nCONTENT;\n\n$return .= \\IPS\\Theme::i()->getTemplate( \"submit\", \"forums\" )->createTopicModOptions( $elements, $modOptions );\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t<\/div>\n\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t<\/div>\t\t\n\t\t\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\n\t\t\t<div class='ipsPadding'>\n\t\t\t\t\nCONTENT;\n\nif ( $hasModOptions ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t<div class='ipsColumns ipsColumns_collapsePhone'>\n\t\t\t\t\t\t<div class='ipsColumn ipsColumn_fluid'>\n\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t<ul class='ipsForm ipsForm_vertical'>\n\t\t\t\t\t\t\nCONTENT;\n\nforeach ( $elements as $collection ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\nCONTENT;\n\nforeach ( $collection as $inputName => $input ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nif ( !\\in_array( $inputName, $modOptions ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t{$input}\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t\t\t<\/ul>\n\t\t\t\t\nCONTENT;\n\nif ( $hasModOptions ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t<div class='ipsColumn ipsColumn_wide'>\n\t\t\t\t\t\t\t\nCONTENT;\n\n$return .= \\IPS\\Theme::i()->getTemplate( \"submit\", \"forums\" )->createTopicModOptions( $elements, $modOptions );\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t<\/div>\n\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t<\/div>\n\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\n\t\t<div class='ipsAreaBackground_reset ipsPadding ipsType_center ipsBorder_top ipsRadius:bl ipsRadius:br'>\n\t\t\t\nCONTENT;\n\nif ( $topic ):\n$return .= <<<CONTENT\n\n\t\t\t<button type='submit' class='ipsButton ipsButton_large ipsButton_primary' tabindex=\"1\" accesskey=\"s\" role=\"button\">\nCONTENT;\n\nif ( $forum->forums_bitoptions['bw_enable_answers'] ):\n$return .= <<<CONTENT\n\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'submit_question_edit', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'submit_topic_edit', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n<\/button>\n\t\t\t\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\n\t\t\t<button type='submit' class='ipsButton ipsButton_large ipsButton_primary' tabindex=\"1\" accesskey=\"s\" role=\"button\">\nCONTENT;\n\nif ( $forum->forums_bitoptions['bw_enable_answers'] ):\n$return .= <<<CONTENT\n\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'submit_question', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'submit_topic', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n<\/button>\n\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t<\/div>\n\t<\/div>\t\n<\/form>\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction createTopicModOptions( $elements, $modOptions ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\n<div class='ipsBox ipsBox--child'>\n\t<h3 class='ipsType_sectionTitle'>\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'topic_moderator_options', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n<\/h3>\n\t<ul class='ipsPadding ipsForm ipsForm_vertical'>\n\t\t\nCONTENT;\n\nforeach ( $elements as $collection ):\n$return .= <<<CONTENT\n\n\t\t\t\nCONTENT;\n\nforeach ( $collection as $inputName => $input ):\n$return .= <<<CONTENT\n\n\t\t\t\t\nCONTENT;\n\nif ( \\in_array( $inputName, $modOptions ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\nCONTENT;\n\nif ( $inputName == 'topic_open_time' or $inputName == 'topic_close_time' ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t<li class='ipsFieldRow ipsClearfix'>\n\t\t\t\t\t\t\t<label class=\"ipsFieldRow_label\" for=\"elInput_\nCONTENT;\n$return .= htmlspecialchars( $input->name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\">\nCONTENT;\n\n$val = \"{$input->name}\"; $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( $val, ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n<\/label>\n\t\t\t\t\t\t\t<ul class='ipsFieldRow_content ipsList_reset cCreateTopic_date'>\n\t\t\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t\t\t<i class='fa fa-calendar'><\/i>\n\t\t\t\t\t\t\t\t\t<input type=\"date\" name=\"\nCONTENT;\n$return .= htmlspecialchars( $input->name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" id=\"elInput_\nCONTENT;\n$return .= htmlspecialchars( $input->name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" class=\"ipsField_short\" data-control=\"date\" placeholder='\nCONTENT;\n\n$return .= htmlspecialchars( str_replace( array( 'YYYY', 'MM', 'DD' ), array( \\IPS\\Member::loggedIn()->language()->addToStack('_date_format_yyyy'), \\IPS\\Member::loggedIn()->language()->addToStack('_date_format_mm'), \\IPS\\Member::loggedIn()->language()->addToStack('_date_format_dd') ), str_replace( 'Y', 'YY', \\IPS\\Member::loggedIn()->language()->preferredDateFormat() ) ), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' value=\"\nCONTENT;\n\nif ( $input->value instanceof \\IPS\\DateTime ):\n$return .= <<<CONTENT\n\nCONTENT;\n$return .= htmlspecialchars( $input->value->format('Y-m-d'), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\nCONTENT;\n$return .= htmlspecialchars( $input->value, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\" data-preferredFormat=\"\nCONTENT;\n\nif ( $input->value instanceof \\IPS\\DateTime ):\n$return .= <<<CONTENT\n\nCONTENT;\n$return .= htmlspecialchars( $input->value->localeDate(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\nCONTENT;\n$return .= htmlspecialchars( $input->value, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\">\n\t\t\t\t\t\t\t\t<\/li>\n\t\t\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t\t\t<i class='fa fa-clock-o'><\/i>\n\t\t\t\t\t\t\t\t\t<input name=\"\nCONTENT;\n$return .= htmlspecialchars( $input->name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n_time\" type=\"time\" size=\"12\" class=\"ipsField_short\" placeholder=\"\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( '_time_format_hhmm', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n\" step=\"60\" min=\"00:00\" value=\"\nCONTENT;\n\nif ( $input->value instanceof \\IPS\\DateTime ):\n$return .= <<<CONTENT\n\nCONTENT;\n$return .= htmlspecialchars( $input->value->format('H:i'), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\">\n\t\t\t\t\t\t\t\t<\/li>\n\t\t\t\t\t\t\t<\/ul>\n\t\t\t\t\t\t<\/li>\n\t\t\t\t\t\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t{$input}\n\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t<\/ul>\n<\/div>\nCONTENT;\n\n\t\treturn $return;\n}}"
VALUE;
