# querylist_beike
##2.编写采集规则

    $rules = array(
       //采集id为one这个元素里面的纯文本内容
      'text' => array('#one','text'),
      //采集class为two下面的超链接的链接
      'link' => array('.two>a','href'),
      //采集class为two下面的第二张图片的链接
      'img' => array('.two>img:eq(1)','src'),
       //采集span标签中的HTML内容
       'other' => array('span','html')
    );
 ##3.开始采集

    // 过程:设置HTML=>设置采集规则=>执行采集=>获取采集结果数据
    $data = QueryList::html($html)->rules($rules)->query()->getData();
     //打印结果
    print_r($data->all());
