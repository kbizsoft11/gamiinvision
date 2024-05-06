<?php

return <<<'VALUE'
"namespace IPS\\Theme;\nclass class_cms_front_widgets extends \\IPS\\Theme\\Template\n{\n\tpublic $cache_key = '52d5aa74b71ee99175f0068e72931ecb';\n\tfunction Blocks( $content ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n{$content}\n\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction Categories( $url, $orientation='vertical' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\nCONTENT;\n\n$catClass = '\\IPS\\cms\\Categories' . \\IPS\\cms\\Databases\\Dispatcher::i()->databaseId;\n$return .= <<<CONTENT\n\n\nCONTENT;\n\n$categories = $catClass::roots();\n$return .= <<<CONTENT\n\n\nCONTENT;\n\nif ( !empty( $categories ) ):\n$return .= <<<CONTENT\n\n\t<div\nCONTENT;\n\nif ( $orientation == \"horizontal\" ):\n$return .= <<<CONTENT\n class=\"ipsBox\"\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n>\n\t\t<h3 class='ipsWidget_title ipsType_reset'>\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'categories', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n<\/h3>\n\t\t<div class='ipsSideMenu ipsCategoriesMenu ipsAreaBackground_reset ipsPad_half'>\n\t\t\t<ul class='ipsSideMenu_list'>\n\t\t\t\t\nCONTENT;\n\nforeach ( $categories as $category ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t<li>\n\t\t\t\t\t\t<a href=\"\nCONTENT;\n$return .= htmlspecialchars( $category->url(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" class='ipsSideMenu_item ipsTruncate ipsTruncate_line'><span class='ipsBadge ipsBadge_style1 ipsPos_right'>\nCONTENT;\n\n$return .= htmlspecialchars( \\IPS\\cms\\Records::contentCount( $category ), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/span><strong class='ipsType_normal'>\nCONTENT;\n$return .= htmlspecialchars( $category->_title, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/strong><\/a>\n\t\t\t\t\t\t\nCONTENT;\n\nif ( $category->hasChildren() ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<ul class=\"ipsSideMenu_list\">\n\t\t\t\t\t\t\t\t\nCONTENT;\n\n$counter = 0;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nforeach ( $category->children() as $idx => $subcategory ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\nCONTENT;\n\n$counter++;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t<li>\n\t\t\t\t\t\t\t\t\t\t\nCONTENT;\n\nif ( $counter >= 5 ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t\t\t<a href='\nCONTENT;\n$return .= htmlspecialchars( $category->url(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' class='ipsSideMenu_item'><span class='ipsType_light ipsType_small'>\nCONTENT;\n\n$pluralize = array( \\count( $category->children() ) - 4 ); $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'and_x_more', ENT_DISALLOWED, 'UTF-8', FALSE ), FALSE, array( 'pluralize' => $pluralize ) );\n$return .= <<<CONTENT\n<\/span><\/a>\n\t\t\t\t\t\t\t\t\t\t\t\nCONTENT;\n\nbreak;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t\t\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t\t\t<a href=\"\nCONTENT;\n$return .= htmlspecialchars( $subcategory->url(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" class='ipsSideMenu_item ipsTruncate ipsTruncate_line'><strong class='ipsPos_right ipsType_small'>\nCONTENT;\n\n$return .= htmlspecialchars( \\IPS\\cms\\Records::contentCount( $subcategory ), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/strong>\nCONTENT;\n$return .= htmlspecialchars( $subcategory->_title, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/a>\n\t\t\t\t\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t\t<\/li>\n\t\t\t\t\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<\/ul>\n\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t<\/li>\n\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t<\/ul>\n\t\t\t<p class='ipsType_center'>\n\t\t\t\t<a href='\nCONTENT;\n$return .= htmlspecialchars( $url->setQueryString('show','categories'), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n' class=''>\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'cms_show_categories', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n &nbsp;<i class='fa fa-caret-right'><\/i><\/a>\n\t\t\t<\/p>\n\t\t<\/div>\n\t<\/div>\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction Database( $database ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\nCONTENT;\n\n$return .= \\IPS\\cms\\Databases\\Dispatcher::i()->setDatabase( \"$database->id\" )->run();\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction DatabaseFilters( $database, $category, $form, $orientation='vertical' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<h3 class='ipsWidget_title ipsType_reset'>\nCONTENT;\n\n$sprintf = array($category->_title); $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'block_DatabaseFilters_title', ENT_DISALLOWED, 'UTF-8', FALSE ), FALSE, array( 'sprintf' => $sprintf ) );\n$return .= <<<CONTENT\n<\/h3>\n<div class='ipsWidget_inner ipsPad'>\n\t{$form}\n<\/div>\n\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction Editor( $content, $orientation='horizontal' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<div class='ipsWidget_inner \nCONTENT;\n\nif ( $orientation == 'vertical' ):\n$return .= <<<CONTENT\nipsPad\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n'>\n\t{$content}\n<\/div>\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction RecordFeed( $records, $title, $orientation='vertical' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\nCONTENT;\n\nif ( !empty( $records )  ):\n$return .= <<<CONTENT\n\n\t<h3 class='ipsWidget_title ipsType_reset'>\nCONTENT;\n$return .= htmlspecialchars( $title, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/h3>\n\t\nCONTENT;\n\nif ( $orientation == 'vertical' ):\n$return .= <<<CONTENT\n\n\t\t<div class='ipsWidget_inner'>\n\t\t\t<ul class='ipsDataList ipsDataList_reducedSpacing ipsContained_container'>\n\t\t\t\t\nCONTENT;\n\nforeach ( $records as $record ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t<li class='ipsDataItem \nCONTENT;\n\nif ( $record->hidden() ):\n$return .= <<<CONTENT\n ipsModerated\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n'>\n\t\t\t\t\t\t<div class='ipsDataItem_icon ipsPos_top'>\n\t\t\t\t\t\t\t\nCONTENT;\n\n$return .= \\IPS\\Theme::i()->getTemplate( \"global\", \"core\" )->userPhoto( $record->author(), 'tiny' );\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t<div class='ipsDataItem_main cWidgetComments'>\n                            \nCONTENT;\n\nif ( $record::database()->options['comments'] ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<div class=\"ipsCommentCount ipsPos_right \nCONTENT;\n\nif ( ( $record->record_comments ) === 0 ):\n$return .= <<<CONTENT\nipsFaded\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\" data-ipsTooltip title='\nCONTENT;\n\n$pluralize = array( $record->record_comments ); $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'num_replies', ENT_DISALLOWED, 'UTF-8', FALSE ), FALSE, array( 'pluralize' => $pluralize ) );\n$return .= <<<CONTENT\n'>\nCONTENT;\n\n$return .= htmlspecialchars( $record->record_comments, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/div>\n                            \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<div class='ipsType_break ipsContained'>\n                                \nCONTENT;\n\nif ( $record->locked() ):\n$return .= <<<CONTENT\n<span><i class='fa fa-lock'><\/i><\/span> \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\n                                \nCONTENT;\n\nif ( $record->isFutureDate() || $record->mapped('pinned') || $record->mapped('featured') || $record->hidden() === -1 || $record->hidden() === 1 || $record->hidden() === -2 ):\n$return .= <<<CONTENT\n\n                                \nCONTENT;\n\nif ( $record->isFutureDate() ):\n$return .= <<<CONTENT\n\n                                <span><span class=\"ipsBadge ipsBadge_icon ipsBadge_small ipsBadge_warning\" data-ipsTooltip title='\nCONTENT;\n$return .= htmlspecialchars( $record->futureDateBlurb(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n'><i class='fa fa-clock-o'><\/i><\/span><\/span>\n                                \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n                                \nCONTENT;\n\nif ( $record->hidden() === -1 ):\n$return .= <<<CONTENT\n\n                                <span><span class=\"ipsBadge ipsBadge_icon ipsBadge_small  ipsBadge_warning\" data-ipsTooltip title='\nCONTENT;\n$return .= htmlspecialchars( $record->hiddenBlurb(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n'><i class='fa fa-eye-slash'><\/i><\/span><\/span>\n                                \nCONTENT;\n\nelseif ( $record->hidden() === -2 ):\n$return .= <<<CONTENT\n\n                                <span><span class=\"ipsbadge ipsBadge_icon ipsBadge_small ipsBadge_warning\" data-ipsTooltip title='\nCONTENT;\n$return .= htmlspecialchars( $record->deletedBlurb(), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n'><i class='fa fa-trash'><\/i><\/span><\/span>\n                                \nCONTENT;\n\nelseif ( $record->hidden() === 1 ):\n$return .= <<<CONTENT\n\n                                <span><span class=\"ipsBadge ipsBadge_icon ipsBadge_small ipsBadge_warning\" data-ipsTooltip title='\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'pending_approval', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n'><i class='fa fa-warning'><\/i><\/span><\/span>\n                                \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n                                \nCONTENT;\n\nif ( $record->mapped('pinned') ):\n$return .= <<<CONTENT\n\n                                <span><span class=\"ipsBadge ipsBadge_icon ipsBadge_small ipsBadge_positive\" data-ipsTooltip title='\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'pinned', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n'><i class='fa fa-thumb-tack'><\/i><\/span><\/span>\n                                \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n                                \nCONTENT;\n\nif ( $record->mapped('featured') ):\n$return .= <<<CONTENT\n\n                                <span><span class=\"ipsBadge ipsBadge_icon ipsBadge_small ipsBadge_positive\" data-ipsTooltip title='\nCONTENT;\n\n$return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'featured', ENT_DISALLOWED, 'UTF-8', FALSE ), TRUE, array(  ) );\n$return .= <<<CONTENT\n'><i class='fa fa-star'><\/i><\/span><\/span>\n                                \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n                                \nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t<a href=\"\nCONTENT;\n$return .= htmlspecialchars( $record->url( \"getPrefComment\" ), ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" title='\nCONTENT;\n\n$sprintf = array(\\IPS\\Member::loggedIn()->language()->addToStack( 'content_db_lang_sl_' . $record::$customDatabaseId, FALSE ), $record->_title); $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'view_this_cmsrecord', ENT_DISALLOWED, 'UTF-8', FALSE ), FALSE, array( 'sprintf' => $sprintf ) );\n$return .= <<<CONTENT\n' class='ipsDataItem_title'>\nCONTENT;\n$return .= htmlspecialchars( $record->_title, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/a>\n\t\t\t\t\t\t\t<\/div>\n\t\t\t\t\t\t\t<p class='ipsType_reset ipsType_medium ipsType_blendLinks'>\n\t\t\t\t\t\t\t\t<span>\nCONTENT;\n\n$htmlsprintf = array($record->author()->link( NULL, NULL, $record->isAnonymous() )); $return .= \\IPS\\Member::loggedIn()->language()->addToStack( htmlspecialchars( 'byline_nodate', ENT_DISALLOWED, 'UTF-8', FALSE ), FALSE, array( 'htmlsprintf' => $htmlsprintf ) );\n$return .= <<<CONTENT\n<\/span><br>\n\t\t\t\t\t\t\t\t<span class=\"ipsType_light\">\nCONTENT;\n\n$val = ( $record->mapped('date') instanceof \\IPS\\DateTime ) ? $record->mapped('date') : \\IPS\\DateTime::ts( $record->mapped('date') );$return .= $val->html();\n$return .= <<<CONTENT\n<\/span>\n\t\t\t\t\t\t\t<\/p>\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t<\/li>\n\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t<\/ul>\n\t\t<\/div>\n\t\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\n\t\t<div class='ipsWidget_inner'>\n\t\t\t<ul class='ipsDataList ipsContained_container'>\n\t\t\t\t\nCONTENT;\n\n$return .= \\IPS\\cms\\Theme::i()->getTemplate( \"listing\", \"cms\", 'database' )->recordRow( null, null, $records );\n$return .= <<<CONTENT\n\n\t\t\t<\/ul>\n\t\t<\/div>\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction Rss( $items, $title, $orientation='vertical' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\nCONTENT;\n\nif ( !empty( $items )  ):\n$return .= <<<CONTENT\n\n\t<h3 class='ipsWidget_title ipsType_reset'>\nCONTENT;\n$return .= htmlspecialchars( $title, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/h3>\n\t\t<div class='ipsWidget_inner'>\n\t\t\t<ul class='ipsDataList ipsDataList_reducedSpacing'>\n\t\t\t\t\nCONTENT;\n\nforeach ( $items as $item ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t<li class='ipsDataItem'>\n\t\t\t\t\t\t<div class='ipsDataItem_main'>\n\t\t\t\t\t\t\t<div class='ipsType_break ipsContained'><a href=\"\nCONTENT;\n$return .= htmlspecialchars( $item['link'], ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" target=\"_blank\" rel=\"noopener\" class='ipsTruncate ipsTruncate_line'>\nCONTENT;\n$return .= htmlspecialchars( $item['title'], ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/a><\/div>\n\t\t\t\t\t\t\t<span class='ipsType_light ipsType_small'>\nCONTENT;\n\n$val = ( $item['date'] instanceof \\IPS\\DateTime ) ? $item['date'] : \\IPS\\DateTime::ts( $item['date'] );$return .= $val->html();\n$return .= <<<CONTENT\n<\/span>\n\t\t\t\t\t\t<\/div>\n\t\t\t\t\t<\/li>\n\t\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t\t<\/ul>\n\t\t<\/div>\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction Wysiwyg( $content, $orientation='horizontal' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<div class='ipsWidget_inner ipsPad ipsType_richText' data-controller='core.front.core.lightboxedImages'>\n\t{$content}\n<\/div>\n\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction pagebuilderoembed( $video ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<div class='ipsPos_center'>{$video}<\/div>\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction pagebuildertext( $text ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n<div>{$text}<\/div>\nCONTENT;\n\n\t\treturn $return;\n}\n\n\tfunction pagebuilderupload( $images, $captions, $urls, $autoPlay, $maxHeight, $orientation='vertical' ) {\n\t\t$return = '';\n\t\t$return .= <<<CONTENT\n\n\n\nCONTENT;\n\nif ( ! \\is_array( $images ) ):\n$return .= <<<CONTENT\n\n<div>\n\t\nCONTENT;\n\nif ( isset( $urls[0] ) ):\n$return .= <<<CONTENT\n\n\t\t<a href=\"\nCONTENT;\n$return .= htmlspecialchars( $urls[0], ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\">\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t<img src=\"\nCONTENT;\n$return .= htmlspecialchars( $images, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" \nCONTENT;\n\nif ( $maxHeight ):\n$return .= <<<CONTENT\nstyle=\"max-height: \nCONTENT;\n$return .= htmlspecialchars( $maxHeight, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\npx; max-width: 100%; width: auto\"\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n alt='' class=\"ipsPageBuilderUpload\">\n\t\nCONTENT;\n\nif ( isset( $captions[0] ) ):\n$return .= <<<CONTENT\n\n\t\t<span class=\"ipsWidget_imageCarousel__caption\">\nCONTENT;\n$return .= htmlspecialchars( $captions[0], ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/span>\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\nCONTENT;\n\nif ( isset( $urls[0] ) ):\n$return .= <<<CONTENT\n\n\t\t<\/a>\n\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n<\/div>\n\nCONTENT;\n\nelse:\n$return .= <<<CONTENT\n\n<div class=\"ipsCarousel ipsClearfix\" data-ipsCarousel \nCONTENT;\n\nif ( $autoPlay ):\n$return .= <<<CONTENT\ndata-ipsCarousel-slideshow\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n \nCONTENT;\n\nif ( !$maxHeight || $orientation=='vertical' ):\n$return .= <<<CONTENT\ndata-ipsCarousel-fullSizeItems\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n data-ipsCarousel-shadows='false'>\n\t<div class='ipsCarousel_inner'>\n\t\t<ul class='ipsClearfix ipsList_reset' data-role='carouselItems'>\n\t\t\t\nCONTENT;\n\nforeach ( $images as $id => $image ):\n$return .= <<<CONTENT\n\n\t\t\t\t<li class='ipsCarousel_item ipsAreaBackground_reset'>\n\t\t\t\t\t<div class='ipsWidget_imageCarousel'>\n\t\t\t\t\t\t\nCONTENT;\n\nif ( isset( $urls[ $id ] ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<a href=\"\nCONTENT;\n$return .= htmlspecialchars( $urls[ $id ], ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\">\n\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<img src=\"\nCONTENT;\n$return .= htmlspecialchars( $image, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n\" \nCONTENT;\n\nif ( $maxHeight ):\n$return .= <<<CONTENT\nstyle=\"max-height: \nCONTENT;\n$return .= htmlspecialchars( $maxHeight, ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\npx; max-width: none\"\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n alt=''>\n\t\t\t\t\t\t\t\nCONTENT;\n\nif ( isset( $captions[ $id ] ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t\t<span class=\"ipsWidget_imageCarousel__caption\">\nCONTENT;\n$return .= htmlspecialchars( $captions[ $id ], ENT_QUOTES | ENT_DISALLOWED, 'UTF-8', FALSE );\n$return .= <<<CONTENT\n<\/span>\n\t\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\nCONTENT;\n\nif ( isset( $urls[ $id ] ) ):\n$return .= <<<CONTENT\n\n\t\t\t\t\t\t\t<\/a>\n\t\t\t\t\t\t\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\t\t\t\t\t<\/div>\n\t\t\t\t<\/li>\n\t\t\t\nCONTENT;\n\nendforeach;\n$return .= <<<CONTENT\n\n\t\t<\/ul>\n\t<\/div>\n\t<span class='ipsCarousel_shadow ipsCarousel_shadowLeft'><\/span>\n    <span class='ipsCarousel_shadow ipsCarousel_shadowRight'><\/span>\n\t<a href='#' class='ipsCarousel_nav ipsHide' data-action='prev'><i class='fa fa-chevron-left'><\/i><\/a>\n\t<a href='#' class='ipsCarousel_nav ipsHide' data-action='next'><i class='fa fa-chevron-right'><\/i><\/a>\n<\/div>\n\n\nCONTENT;\n\nendif;\n$return .= <<<CONTENT\n\n\nCONTENT;\n\n\t\treturn $return;\n}}"
VALUE;
