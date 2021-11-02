<?php
function create_list($hizuke,$flg) {
  global
    $city_arr ,
    $city_err ,
    $arr ,
    $tblFP ,
    $yesterday,
    $yobi_arr;

  $date       = new DateTime();
  $date->sub(new DateInterval('P1D'));
  $yesterday  = explode('-',$date->format('Y-n-j-w'));

  $yobi_arr   = array(
    '0'       => '日',
    '1'       => '月',
    '2'       => '火',
    '3'       => '水',
    '4'       => '木',
    '5'       => '金',
    '6'       => '土'
  );


  $city_arr   = array(
    '盛岡市','宮古市','大船渡市','花巻市','北上市','久慈市','遠野市','一関市','陸前高田市','釜石市','二戸市','八幡平市','奥州市','滝沢市','雫石町','葛巻町','岩手町','紫波町','矢巾町','西和賀町','金ヶ崎町','平泉町','住田町','大槌町','山田町','岩泉町','田野畑村','普代村','軽米町','野田村','九戸村','洋野町','一戸町'
  );
  $city_err   = array(
    '金ケ崎'  => '金ヶ崎'
  );
  $arr        =  array(
    'area'    => 0,
    'name'    => 1,
    'kana'    => 2,
    'zip'     => 4,
    'address' => 5,
    'tel'     => 6,
    'url'     => 7,
    'plan'    => 8,
    'map'     => 3,
    'memo'    => 9,
    'key'     => 10
  );
  // 'pref'  => 4,
  // 'city'  => 5,
  // 'street'=> 6,
  // 'buil'  => 7,
  // file open
  $html_in  = $hizuke . 'index_' . $flg . '.html';
  $html_out = $hizuke . 'index_' . $flg . '_create.html';

  $hpFP  = fopen("./upload/".$html_in,"r") or exit("htmlファイルをopenできません。");
  $tblFP = fopen("./".$html_out,"w");

  $marker  = <<< EOD
                    <!-- < list add > -->\r\n
  EOD;

  // 該当タグまでhtmlを読み込み&出力
  if($hpFP !== false) {
    while($hp = fgets($hpFP)) {
      if(preg_match('/(\S*)< list add >(\S*)/',$hp) === 1) {
        putElement($hizuke,$flg);
        fwrite($tblFP,$marker);
      }
      else {
        $hp = day_check($hp);
        fwrite($tblFP,$hp);
      }
    }
  }
  fclose($hpFP);
  fclose($tblFP);
}

function putElement($hizuke,$flg) {
  global
    $city_arr ,
    $city_err ,
    $arr ,
    $tblFP;

  $csv_in = $hizuke . 'index_' . $flg . '.csv';
  $csvFP = fopen("./upload/".$csv_in, "r");
  if($csvFP !== false) {
    while($csv = fgetcsv($csvFP)) {
      // utf-8変換
      $cnv = mb_convert_encoding($csv,"utf-8","sjis-win");
      var_dump($cnv);
      echo '<br>';
      if($flg == 'stay') {
        if($cnv[$arr['key']] !== '宿泊' && $cnv[$arr['key']] !== '宿泊施設') {
          continue;
        }
      }
      else if($flg == 'sightseeing') {
        if($cnv[$arr['key']] !== '観光' && $cnv[$arr['key']] !== '観光施設') {
          continue;
        }
      }
      // 住所よりエリア取得
      $city     = '';
      foreach($city_arr as $check) {
        if(preg_match('/'.$check.'/',$cnv[$arr['address']]) === 1) {
          $city = $check; // 該当した市町村名を退避
        }
      }
      // イレギュラー市町村名のチェック
      if($city === '') {
        foreach($city_err as $check => $value) {
          if(preg_match('/'.$check.'/',$cnv[$arr['address']]) === 1) {
            $city = $value; // 該当した市町村名を退避
            $cnv[$arr['address']] = str_replace($check,$value,$cnv[$arr['address']]);
          }
        }
      }
      if($city === '') {
        $city = '盛岡市'; // 住所に市町村が未記入時は「盛岡市」とする
      }
      // HPのURL行など
      if(preg_match('/^https?:\/\//',$cnv[$arr['url']]) !== 1) {
        // http:// もしくは https:// が存在しない場合
        $url_bk = $cnv[$arr['url']];
        $cnv[$arr['url']] = 'https://'.$url_bk;
      }
      if(preg_match('/https?:\/{2}[\w\/:%#\$&\?\(\)~\.=\+\-]+/',$cnv[$arr['url']]) !== 1) {
        // url以外の場合
        echo 'urlとして不備あり：' . $cnv[$arr['url']] . ' / urlなしに置換';
        $cnv[$arr['url']] = '';
      }
      if(!empty($cnv[$arr['url']])) {
        $url_st = '<a href="'.$cnv[$arr['url']].'" target="_blank">';
        $url_ed = '</a>';
        $hp     = <<<EOD
        <a href="{$cnv[$arr['url']]}" target="_blank">HP</a>
        EOD;
      }
      else {
        $url_st = $url_ed = '';
        $hp = '-';
      }
      // google map
      if(!empty($cnv[$arr['map']])) {
        $map_st = '<a href="'.$cnv[$arr['map']].'" class="link_underline purple-link" target="_blank">';
        $map_ed = '</a>';
      }
      else {
        $map_st = $map_ed = '';
      }

      // ワーケーションの有無
      if(!empty($cnv[$arr['plan']])) {
        $workcation = 'あり';
        $work       = <<< EOD
        <span>{$cnv[$arr['plan']]}</span>
        EOD;
      }
      else {
        $workcation = 'なし';
        $work       = '-';
      }

      // 備考の有無
      if(!empty($cnv[$arr['memo']])) {
        $memo       = <<< EOD
        <span>{$cnv[$arr['memo']]}</span>
        EOD;
      }
      else {
        $memo       = '-';
      }


      if($flg == 'stay') {
        // ヒアドキュメントに埋め込み
        $html = <<< EOD
                  <tr class="list_item" data-area="{$cnv[$arr['area']]}" data-category="宿泊施設" data-city="{$city}" data-workcation="{$workcation}">
                    <th scope="row text-center">{$cnv[$arr['area']]}</th>
                    <td class="d-none">{$cnv[$arr['kana']]}</td>
                    <td>{$cnv[$arr['name']]}</td>
                    <td>〒{$cnv[$arr['zip']]}<br>{$map_st}{$cnv[$arr['address']]}{$map_ed}</td>
                    <td>{$cnv[$arr['tel']]}</td>
                    <td>{$hp}</td>
                    <td>{$work}</td>
                    <td>{$memo}</td>
                  </tr>\r\n
        EOD;
      }
      else if($flg == 'sightseeing') {
        // ヒアドキュメントに埋め込み
        $html = <<< EOD
                  <tr class="list_item" data-area="{$cnv[$arr['area']]}" data-category="観光施設" data-city="{$city}" data-workcation="">
                    <th scope="row text-center">{$cnv[$arr['area']]}</th>
                    <td class="d-none">{$cnv[$arr['kana']]}</td>
                    <td>{$cnv[$arr['name']]}</td>
                    <td>〒{$cnv[$arr['zip']]}<br>{$map_st}{$cnv[$arr['address']]}{$map_ed}</td>
                    <td>{$cnv[$arr['tel']]}</td>
                    <td>{$hp}</td>
                    <td>{$memo}</td>
                  </tr>\r\n
        EOD;

      }
      // $html = <<< EOD
      //           <tr class="list_item" data-area="{$cnv[$arr['area']]}" data-category="宿泊施設" data-city="{$city}" data-workcation="{$workcation}">
      //             <th scope="row text-center">{$cnv[$arr['area']]}</th>
      //             <td class="d-none">{$cnv[$arr['kana']]}</td>
      //             <td>{$cnv[$arr['name']]}</td>
      //             <td>〒{$cnv[$arr['zip']]}<br>{$map_st}{$cnv[$arr['pref']]}{$cnv[$arr['city']]}{$cnv[$arr['street']]}{$cnv[$arr['buil']]}{$map_ed}</td>
      //             <td>{$cnv[$arr['tel']]}</td>
      //             <td>{$hp}</td>
      //             <td>{$work}</td>
      //           </tr>\r\n
      // EOD;
      ?>
      <?php var_dump($html); ?>
      <?php fwrite($tblFP,$html); ?>
    <?php
    }
  }

  fclose($csvFP);
}

function day_check($line) {
  global $yesterday ,
         $yobi_arr;
  if(preg_match('/令和(\d*)年(\d*)月(\d*)日\((\S*)\)申し込み受け付け分を/',$line)) {
    $year  = intval($yesterday[0]) - 2018;
    $line = <<<EOD
      <p class="small">令和{$year}年{$yesterday[1]}月{$yesterday[2]}日({$yobi_arr[$yesterday[3]]})申し込み受け付け分を掲載しています。対象施設一覧は週１回程度の頻度で更新しております。あらかじめご了承ください。</p>
      EOD;
  }
  return $line;
}

?>
