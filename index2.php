<?php

//SESSIONスタート
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$gen_name = $_SESSION['gen_name'];

$pill_num = $_GET["id"];
$_SESSION["pill_num"] = $pill_num;


require_once('funcs.php');
//ログインチェック
// loginCheck();
$pdo = db_conn();

// １．杭番号情報抽出
$stmt = $pdo->prepare("select * from design where pill_num = :pill_num");
$stmt->bindValue(':pill_num', $pill_num, PDO::PARAM_STR);
$status = $stmt->execute();
foreach ($stmt as $row) {
  $pill_sign =$row['pill_sign'];
  $virtilength =$row['virtilength'];
  $stcore_numX =$row['stcore_numX'];
  $stcore_numY =$row['stcore_numY'];
}


?>




<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
<title>CFT-con施工管理</title>
</head>

<header style="position: fixed;width:100%;z-index: 9999;">
  <nav class="navbar navbar-default" style="background:linear-gradient(to bottom, #8EA9DB 70%, #D9EAFF 100%); border-color:#305496;font-size:15px;display:flex;justify-content:space-between;">
    <div class="container-fluid"><p style="font-size: 24px;color:#E2EFDA;font-weight: bold;">CFTコンクリート施工管理システム</p></div>
    <div>
      <a  style="color:#FFFFFF;" href="pillselect.php">柱選択画面へ</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>
</header>


<body>


<p style="height:100px;"></p>
<h3>施工管理画面</h3>
<div style="display: flex; justify-content:flex-start;margin:5px;">
    <div>
        <p style="font-size:20px;">現場名：<?= $gen_name ?></p>
        <p style="font-size:20px;">杭番号：<?= $pill_num ?></p>
    </div>
    <div>
        <input type="button" onclick="location.href='./accept.php'" value="受入検査へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;width:120px;'>
        <input type="button" onclick="location.href='./time.php'" value="打上高管理へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>
        <input type="button" onclick="window.open('https://d28000001pumpeaw.my.salesforce-sites.com/', '_blank')" value="配車管理へ" style="color:white; border-color:#3b82f6; font-size:18px; margin:10px; background:#8EA9DB; border-radius:10px;">


    </div>
</div>
    <div style="display: flex; justify-content:space-around;margin:5px;width:300px;padding:0px;">
        <p style="font-size:16px; margin:3px;">杭番号：<?= $pill_num ?></p>
        <p style="font-size:16px; margin:3px;">杭符号：<?= $pill_sign ?></p>
    </div>
    <div style="display: flex; justify-content:space-around;margin:5px;width:300px;padding:0px;">
        <p style="font-size:16px; margin:3px;">位置：<?=  $stcore_numX ?> － <?=  $stcore_numY ?></p>
        <p style="font-size:16px; margin:3px;">柱長：<?= $virtilength ?></p>

    </div>
















<!-- <div id="video">
 <iframe
   id="eizo" 
   src="http://10.58.224.6/live.asp?r=201610270.17104665163885002" 
   title="PC画面">
  </iframe>
</div> -->

<div style="display: flex; justify-content:space-around;margin:5px;width:300px;padding:0px;">
      <div  id="video1">
        <p>◆搬入口状況</p> 
          <iframe 
          width="400" height="300" src="https://www.youtube-nocookie.com/embed/9b8hzFfW5V0?si=T1kv2b6fwH9yvcPI" 
          title="YouTube video player" 
          frameborder="0" 
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
          referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
          </iframe>
      </div>


  
      <div id="video2">
        <p>◆充填状況確認</p>
        <div style="margin:0px auto; overflow:hidden;padding:0;width:400px;height:300px;" >
        <iframe
          src="http://10.58.224.6/live.asp?r=201610270.8554343267928939"
          id="MJPEG_streaming"
          style="transform: scale(0.6); transform-origin: top left; margin-top:-75px; margin-left:-160px;"
          width="1000" height="900" 
          title="PC画面2">
          </iframe>
          </div>
        <p style="font-size:16px; color:white; position:absolute; top:400px; left:450px;">杭番号：<?= $pill_num ?></p>
      <!-- http://218.219.233.189/viewer/live/ja/live.html"  "position:absolute; top:300px; left:350px;" "transform: scale(0.5);"-->

      </div>
      </div>
  







 
 











  <!-- ここから上にコードを書く -->
  <!-- この中に記述していく -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="js/main.js"></script>
</body>

</html>