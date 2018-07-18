<?php

//分页正则
$regular_page = '/"totalPage":([\d]+?),/is';

//a标签内容
$regular_acontent = '/<a.*?>(.*?)<\/a>/is';

//li正则表达式
$regular_li = '/clear xiaoquListItem CLICKDATA.*?<\/li>/is';

//li中表与url匹配
$regular_title = '/<div class="title">.*?href="(.*?)".*?>(.*?)<\/a>/is';

//li中的house与a标签
$regular_house = '/<div class="houseInfo">.*?<\/div>/is';
$a = '/<a.*?>(.*?)<\/a>/is';

//li中的positionInfo标签
$regular_houseinfo = '/<div class="positionInfo">.*?<\/div>/is';

//li中的totalPrice均价
$regular_price = '/totalPrice.*?<span>(.*?)<\/span>/is';

//li中的xiaoquListItemSellCount 在售数量
$regular_sale = '/xiaoquListItemSellCount.*?<span>(.*?)<\/span>/is';

//li中的tag
$regular_tag = '/tagList.*?<span>(.*?)<\/span>/is';

//匹配utf8汉字和数字
$regular_characters = '/[\x{4e00}-\x{9fa5}\d]+/u';

//小区信息正则'
$regular_community = '/class="content".*?class="houseRecord"/';

//小区名称 正则表达式
$regular_communityName = '/class="communityName".*?<a.*?>(.*?)<\/a>/is';

//小区所在区域
$regular_communityArea = '/class="areaName".*?class="info"(.*?)<\/div>/is';

//房子信息room
$regular_room = '/class="room".*?class="type"/is';

//房子信息type
$regular_roomtype = '/class="type".*?class="area"/is';

//房子信息area
$regular_roomarea = '/class="area".*?class="aroundInfo"/is';
