<?php
require_once('create_list.php');

$day    = new DateTime();
$hizuke = $day->format('ymd');


$flg      = '';
if(isset($_FILES)) {
  if(isset($_FILES['stay_in_html']) && is_uploaded_file($_FILES['stay_in_html']['tmp_name']) &&
  isset($_FILES['stay_in_csv']) && is_uploaded_file($_FILES['stay_in_csv']['tmp_name'])) {
    $flg  = 'stay';
  }
  if(isset($_FILES['sightseeing_in_html']) && is_uploaded_file($_FILES['sightseeing_in_html']['tmp_name']) &&
  isset($_FILES['sightseeing_in_csv']) && is_uploaded_file($_FILES['sightseeing_in_csv']['tmp_name'])) {
    $flg  = 'sightseeing';
  }
}
$category    =  '不明な施設';
if(isset($_POST['stay_submit'])) {
  $category  =  '宿泊施設';
}
else if(isset($_POST['sightseeing_submit'])) {
  $category  =  '観光施設';
}
$msg         =  '正常な処理';
if($flg == '') {
  $msg ='inputファイル指定漏れのため処理不能です。前画面に戻りファイルを指定してください。';
}

/* 宿泊施設 */
// 前回のhtml
if(isset($_FILES) && isset($_FILES['stay_in_html']) && is_uploaded_file($_FILES['stay_in_html']['tmp_name'])){
  // nothing upload folder,make a directory.
  if(!file_exists('upload')){
      mkdir('upload');
  }
  // upload before html
  $a = 'upload/' . $hizuke . 'index_stay.html';
  if(move_uploaded_file($_FILES['stay_in_html']['tmp_name'], $a)){
      $msg = $a. 'のアップロードに成功しました';
  }else {
      $msg = 'htmlのアップロードに失敗しました';
      exit($msg);
  }
}
// 追加分のcsv
if(isset($_FILES) && isset($_FILES['stay_in_csv']) && is_uploaded_file($_FILES['stay_in_csv']['tmp_name'])){
  // nothing upload folder,make a directory.
  if(!file_exists('upload')){
      mkdir('upload');
  }
  // upload before csv
  $a = 'upload/' . $hizuke . 'index_stay.csv';
  if(move_uploaded_file($_FILES['stay_in_csv']['tmp_name'], $a)){
      $msg = $a. 'のアップロードに成功しました';
  }else {
      $msg = 'csvのアップロードに失敗しました';
      exit($msg);
  }
}

/* 観光施設 */
// 前回のhtml
if(isset($_FILES) && isset($_FILES['sightseeing_in_html']) && is_uploaded_file($_FILES['sightseeing_in_html']['tmp_name'])){
  // nothing upload folder,make a directory.
  if(!file_exists('upload')){
      mkdir('upload');
  }
  // upload before html
  $a = 'upload/' . $hizuke . 'index_sightseeing.html';
  if(move_uploaded_file($_FILES['sightseeing_in_html']['tmp_name'], $a)){
      $msg = $a. 'のアップロードに成功しました';
  }else {
      $msg = 'htmlのアップロードに失敗しました';
      exit($msg);
  }
}
// 追加分のcsv
if(isset($_FILES) && isset($_FILES['sightseeing_in_csv']) && is_uploaded_file($_FILES['sightseeing_in_csv']['tmp_name'])){
  // nothing upload folder,make a directory.
  if(!file_exists('upload')){
      mkdir('upload');
  }
  // upload before csv
  $a = 'upload/' . $hizuke . 'index_sightseeing.csv';
  if(move_uploaded_file($_FILES['sightseeing_in_csv']['tmp_name'], $a)){
      $msg = $a. 'のアップロードに成功しました';
  }else {
      $msg = 'csvのアップロードに失敗しました';
      exit($msg);
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>施設追加処理 | いわてに泊まって巡って、癒し旅キャンペーン</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</head>
<body>
  <main>
    <div class="container">
      <h1 class="text-center">いわてに泊まって巡って、癒し旅キャンペーン</h1>
      <div class="py-5 text-center">
        <h2 class="text-center border p-3 d-inline-block"><?php echo $category; ?>追加処理 &gt; 結果</h2>
      </div>
      <div class="mt-5">
        <div class="row justify-content-center">
          <div class="col-12">
            <p class="lead p-3 mb-2 bg-primary text-white text-center">施設追加処理を実行しました。作成された日付ファイルを確認してください。</p>
            <div class="my-5">
              <div class="text-center">
                <a class="button" href="../create.html">施設追加処理画面に戻る</a>
              </div>
            </div>
            <?php
            if($flg != '') {
              create_list($hizuke,$flg);
            }
            else { ?>
              <div class="text-center py-5">
                <?php echo $msg; ?>
              </div>
            <?php
            } ?>
          </div>
          <div class="my-5">
            <div class="text-center">
              <a class="button" href="../create.html">施設追加処理画面に戻る</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

</body>
</html>
