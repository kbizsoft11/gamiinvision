<?php

return <<<'VALUE'
"namespace IPS\\Theme;\nclass class_bimchatbox_front_widgets extends \\IPS\\Theme\\Template\n{\n\tpublic $cache_key = '01e7aa016a7dd2866c5a24a66e21f7b8';\n\tfunction bimchatbox( $orientation='vertical' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\nCONTENT;\n\n$return .= \\IPS\\Theme::i()->getTemplate( \"chat\", \"bimchatbox\" )->main( $orientation );\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}}"
VALUE;
