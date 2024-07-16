<?php

//SESSIONスタート
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$gen_name = $_SESSION['gen_name'];
$pill_num = $_SESSION["pill_num"];
$accept_id =  $_GET["id"];


var_dump($pill_num);



require_once('funcs.php');
//ログインチェック
// loginCheck();
$pdo = db_conn();

// １．杭番号情報抽出
$stmt = $pdo->prepare("select * from design where pill_num = :pill_num && gen_name=:gen_name");
$stmt->bindValue(':pill_num', $pill_num, PDO::PARAM_STR);
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();
foreach ($stmt as $row) {
  $pill_sign =$row['pill_sign'];
  $virtilength =$row['virtilength'];
  $stcore_numX =$row['stcore_numX'];
  $stcore_numY =$row['stcore_numY'];
}

// ２．登録情報検索
$stmt2 = $pdo->prepare("SELECT COUNT(*) AS count FROM conaccept WHERE gen_name=:gen_name && pill_num = :pill_num");
$stmt2->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$stmt2->bindValue(':pill_num', $pill_num, PDO::PARAM_STR);
$status2 = $stmt2->execute();

if ($status2 === false) {
    // エラーハンドリング
    sql_error($stmt2->errorInfo());
} else {
    $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
    $num_times = $result2["count"] + 1;
    $_SESSION["num_times"] = $num_times;
}

// ３．データ表示

$view_x = "<tr align='center' style='background: #BDD7EE;color:#833C0C;'><th align='center' width:5%;>検査回数</th><th  align='center'  width:10%;>日時</th><th align='center' width:10%;>スランプ</th><th width:5%;>空気量</th><th width:14%;>Con温度</th><th width:5%;>塩化物</th><th width:5%;>50cm到達</th><th width:5%;>停止時間</th><th width:5%;>分離抵抗</th><th width:5%;>合否判定</th><th width:14%;>修正</th><th width:10%;>削除</th></tr>";
$view_bunri = "";

$stmt3 = $pdo->prepare("SELECT * FROM conaccept WHERE gen_name=:gen_name && pill_num = :pill_num");
$stmt3->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$stmt3->bindValue(':pill_num', $pill_num, PDO::PARAM_STR);
$status3 = $stmt3->execute();

if ($status3 === false) {
    // execute（SQL実行時にエラーがある場合）
    $error = $stmt3->errorInfo();
    exit("ErrorQuery:".$error[2]);
} else {

    while( $result3 = $stmt3->fetch(PDO::FETCH_ASSOC)){ 
      //GETデータ送信リンク作成
      // <a>で囲う。
      $view_x .= '<tr align="center"><td>'.$result3['num_times'].'</td>';
      $view_x .= '<td>'.$result3['datetime'].'</td>';
      $view_x .= '<td>'.$result3['slump_ac'].'</td>';
      $view_x .= '<td>'.$result3['air_ac'].'</td>';
      $view_x .= '<td>'.$result3['temp_ac'].'</td>';
      $view_x .= '<td>'.$result3['ion_ac'].'</td>';
      $view_x .= '<td>'.$result3['reach_50'].'</td>';
      $view_x .= '<td>'.$result3['stop_time'].'</td>';
      $view_x .= '<td>'.$result3['bunri'].'</td>';
      $view_x .= '<td>'.$result3['gouhi'].'</td>';
 
      $view_x .= '<td id="ss"><a href=accept_ss.php?id='.$result3['id'].'>修正</td>';
      $view_x .= '<td id="aa"><a href=accept_aa.php?id='.$result3['id'].'>削除</td>';
      $view_x .= '</tr>';




  }
}


// ４．修正データ抽出
$slump_ac ="";
$air_ac ="";
$temp_ac ="";
$stcore_numY2 ="";
$reach_50 ="";
$stop_time ="";




$stmt4 = $pdo->prepare("SELECT * FROM conaccept WHERE id=:accept_id");
$stmt4->bindValue(':accept_id', $accept_id, PDO::PARAM_STR);
$status4 = $stmt4->execute();
foreach ($stmt4 as $row) {
  $num_times2 = $row['num_times'];
  $slump_ac =$row['slump_ac'];
  $air_ac =$row['air_ac'];
  $temp_ac =$row['temp_ac'];
  $ion_ac =$row['ion_ac'];
  $reach_50 =$row['reach_50'];
  $stop_time =$row['stop_time'];
  $memo =$row['memo'];


      if($row['bunri'] == '良好'){
        $view_bunri = "<option value='-'>－</option>
        <option value='良好' selected>良好</option>
        <option value='不良'>不良</option>";
      }else if($row['bunri'] == '不良'){
        $view_bunri = "<option value='-'>－</option>
        <option value='良好'>良好</option>
        <option value='不良' selected>不良</option>";
      }else{
        $view_bunri = "<option value='-'>－</option>
        <option value='良好'>良好</option>
        <option value='不良'>不良</option>";
      }

      if($row['gouhi'] == '合格'){
          $view_gouhi = "<option value='-'>－</option>
          <option value='合格' selected>合格</option>
          <option value='不合格'>不合格</option>";
        }else if($row['gouhi'] == '不合格'){
          $view_gouhi = "<option value='-'>－</option>
          <option value='合格'>合格</option>
          <option value='不合格' selected>不合格</option>";
        }else{
          $view_gouhi = "<option value='-'>－</option>
          <option value='合格'>合格</option>
          <option value='不合格'>不合格</option>";
        }



}


?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css" />
<!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
<style>div{padding: 10px;font-size:16px;}</style>
<title>受入検査</title>
</head>
<body>

<header style="position: fixed;width:100%;z-index: 9999;top: 0;left: 0;margin: 0;padding: 0;">
  <nav class="navbar navbar-default" style="background:linear-gradient(to bottom, #8EA9DB 70%, #D9EAFF 100%); border-color:#305496;font-size:15px;display:flex;justify-content:space-between;">
    <div class="container-fluid"><p style="font-size: 20px;color:#E2EFDA;font-weight: bold;">CFTコンクリート施工管理システム</p></div>
    <div>
      <a class="navbar-brand" href="index2.php?id=<?= $pill_num ?>">施工管理へ</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>
</header>

<p style="height:100px;"></p>
  <!-- <p style="font-size:20px;">現場名：<?= $gen_name ?></p> -->
<h3>受入検査</h3>
  <div style="display: flex; justify-content:flex-start;margin:5px;">
      <div>
          <p style="font-size:20px;">現場名：<?= $gen_name ?></p>
          <p style="font-size:20px;">杭番号：<?= $pill_num ?></p>
      </div>
      <div>
          <input type="button" onclick="location.href='./index2.php?id=<?= $pill_num ?>'" value="施工管理へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;width:120px;'>
          <input type="button" onclick="location.href='./time.php'" value="打上高管理へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>
      </div>
  </div>

<form name="form21" action="accept_update.php" method="post">
    <div style="margin-left:20px;">
        <p>検査回数:　<?= $num_times2 ?>回目</p>
        <span id="view_clock"></span>
 
     </div>
            <div style="display: flex; justify-content:space-around;margin:5px;width:300px;padding:0px;">
                <p style="font-size:16px; margin:3px;">杭番号：<?= $pill_num ?></p>
                <p style="font-size:16px; margin:3px;">杭符号：<?= $pill_sign ?></p>
            </div>
            <div style="display: flex; justify-content:space-around;margin:5px;width:300px;padding:0px;">
                <p style="font-size:16px; margin:3px;">位置：<?=  $stcore_numX ?> － <?=  $stcore_numY ?></p>
                <p style="font-size:16px; margin:3px;">柱長：<?= $virtilength ?></p>
            </div>

        <div style="display: flex; justify-content:space-around;margin:10px; width:700px;">
                    <div style="width:400px">
                         <input type="hidden" name="Id" value=<?= $accept_id ?> />
                         <input type="hidden" name="Num_times" value=<?= $num_times ?> />
                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                              <p style="margin:5px;width:170px">〇スランプフロー:</p>
                              <input type="text" name="Slump" style="width:80px" value='<?= $slump_ac ?>'/>
                              <p style="margin:5px;">(cm)</p>
                          </div>

                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                              <p style="margin:5px;width:170px">〇空 気 量:</p>
                              <input type="text" name="Air" style="width:80px" value='<?= $air_ac ?>' />
                              <p style="margin:5px;">(％)</p>
                          </div>

                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                          <p style="margin:5px;width:170px">〇コンクリート温度:</p>
                          <input type="text" name="Temp" style="width:80px" value='<?= $temp_ac ?>' />
                          <p style="margin:5px;">(℃)</p>
                          </div>

                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                          <p style="margin:5px;width:170px">〇塩化物イオン量:</p>
                          <input type="text" name="Chlo" style="width:80px" value='<?= $ion_ac ?>'/>
                          <p style="margin:5px;">(kg/m3)</p>
                          </div>

                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                          <p style="margin:5px;width:170px">〇分離抵抗性</p>
                          <select name='Bunri' style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background-color:#8EA9DB; border-radius:5px; margin:0px; width:80px;'>
                          <?= $view_bunri ?>
                          </select>
                          </div>

                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                              <p style="margin:5px;width:170px">〇合否判定</p>
                              <select name='Gouhi' style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background-color:#8EA9DB; border-radius:5px; margin:0px; width:80px;'>
                               <?= $view_gouhi ?>
                              </select>
                          </div>

                          <p>備考</p>
                          <textarea name="Memo" style="width:400px; height:75px; vertical-align:top;"><?= $memo ?></textarea> 
                       </div>

                      <div style="width:350px">
                          <p>＊50cm到達時間・停止時間は参考値</p>
                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                          <p style="margin:5px;width:170px">〇50㎝到達時間(秒):</p>
                          <input type="text" name="Arr50" style="width:80px" value='<?= $reach_50 ?>'/>
                          <p style="margin:5px;">秒</p>
                          </div>
                          
                          <div style="display: flex; justify-content:flex-start;margin:5px;padding:5px;width:400px;">
                          <p style="margin:5px;width:170px">〇停止時間(秒):</p>
                          <input type="text" name="Stop_time" style="width:80px" value='<?= $stop_time ?>'/>
                          <p style="margin:5px;">秒</p>
                          </div>

                          <input style="margin:10px;font-size:16px;" type="submit" value="修正" />



                            <!-- <form action="送信先パス" method="post" enctype="multipart/form-data"> -->
                              <dl style="margin-left:20px;">
                                <dd>
                                  <div>
                                      <label><span>〇カメラ（全景）</span>
                                      <input type="file" capture="environment" accept="image/*"></label>
                                  </div>
                                  <div>
                                        <label><span>〇カメラ（黒板）</span>
                                        <input type="file" capture="environment" accept="image/*"></label>
                                  </div>
                                </dd>
                              </dl>
                       </div>
                    </div>
        
</form>

<div style="margin-left:20px;">
 <p style="margin:0px 0px 0px 10px;">■受入検査記録値</p>
 <table style="font-size: 12px;width: 800px;">
 <?= $view_x ?>
 </table>
</div>








<!-- 時計表示 -->
<script type="text/javascript">
timerID = setInterval('clock()',500); //0.5秒毎にclock()を実行

function clock() {
	document.getElementById("view_clock").innerHTML = getNow();
}

function getNow() {
	var now = new Date();
	var year = now.getFullYear();
	var mon = now.getMonth()+1; //１を足すこと
	var day = now.getDate();
	var hour = now.getHours();
	var min = now.getMinutes();
	var sec = now.getSeconds();

	//出力用
	var s = year + "年" + mon + "月" + day + "日" + hour + "時" + min + "分" + sec + "秒"; 
	return s;
}
</script>



</body>
</html>

