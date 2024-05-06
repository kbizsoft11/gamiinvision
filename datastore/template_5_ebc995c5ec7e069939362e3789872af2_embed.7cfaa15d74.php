<?php

return <<<'VALUE'
"namespace IPS\\Theme;\nclass class_bimchatbox_front_embed extends \\IPS\\Theme\\Template\n{\n\tpublic $cache_key = '01e7aa016a7dd2866c5a24a66e21f7b8';\n\tfunction chatvars( $emoticons, $badwords ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<script type='text\/javascript'>\n\tips.setSetting( 'chatbox_conf_interval', \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_interval;\n$return .= <<<CONTENT\n );\n\tips.setSetting( 'chatbox_soundEnabled', ips.utils.db.get( 'chatbox', 'sounds' ) );\n\tips.setSetting( 'chatbox_topStyle', \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_ordertop;\n$return .= <<<CONTENT\n );\t\n\tips.setSetting( 'chatbox_maxMSG', \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_chatlimit;\n$return .= <<<CONTENT\n );\n\tips.setSetting( 'chatbox_maxEmoticons', \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_maxemoticons;\n$return .= <<<CONTENT\n );\t\n\tips.setSetting( 'chatbox_Emoticons', \nCONTENT;\n\n$return .= json_encode( $emoticons );\n$return .= <<<CONTENT\n );\t\r\n\tips.setSetting( 'badwords', \r\n\t\t\nCONTENT;\n\n$return .= json_encode( $badwords );\n$return .= <<<CONTENT\n\r\n\t);\t\n\tips.setSetting( 'chatbox_imgPost', \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_imgPost;\n$return .= <<<CONTENT\n );\t\r\n\tips.setSetting( 'chatbox_videoPost', \nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_videoPost;\n$return .= <<<CONTENT\n );\t\r\n\tips.setSetting( 'chatbox_giphy', '\nCONTENT;\n\n$return .= \\IPS\\Settings::i()->chatbox_conf_giphy;\n$return .= <<<CONTENT\n' );\t\r\n\tips.setSetting( 'chatbox_24h', \nCONTENT;\n\nif ( \\IPS\\Settings::i()->chatbox_conf_timeformat == '24' ):\n$return .= <<<CONTENT\ntrue\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\nfalse\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n );\t\n\tips.setSetting( 'chatbox_getAll', false );\n\t\nCONTENT;\n\nif ( \\IPS\\Member::loggedin()->member_id ):\n$return .= <<<CONTENT\n\n\t\tips.setSetting( 'chatbox_myname', '\nCONTENT;\n\n$return .= htmlspecialchars( \\IPS\\Member::loggedin()->name, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' );\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\r\n\tips.setSetting( 'chatbox_version', '\nCONTENT;\n\n$return .= htmlspecialchars( \\IPS\\Application::load('bimchatbox')->version, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' );\t\n<\/script>\nCONTENT;\n\n\t\treturn $return;\n}}"
VALUE;