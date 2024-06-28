<?php

//SESSIONスタート
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$gen_name = $_SESSION['gen_name'];


require_once('funcs.php');
//ログインチェック
// loginCheck();
$pdo = db_conn();

// 登録情報検索
$stmt = $pdo->prepare("SELECT COUNT(*) FROM constmanege WHERE gen_name=:gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();
if ($status == false) {
  sql_error($status);
} else {
  $result = $stmt->fetch();//ここを追記！
}

$type = '';
$strength = '';

$flow = '';
$range = '0.0';
$range2 = '1.5';
$air = '3.0';
$chlomax = 0.3;
$tempmax = 35;
$tempmin = 5;

$status = $result ["COUNT(*)"];
$_SESSION["status"] = $status;


if($result ["COUNT(*)"]!=0){
$stmt2 = $pdo->prepare("SELECT * from constmanege where gen_name=:gen_name");
$stmt2->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status2 = $stmt2->execute();

//4．データ表示
if ($status2 == false) {
  sql_error($status2);
} else {
  $result2 = $stmt2->fetch();//ここを追記！
}
$type = $result2 ["contype"];
$strength = $result2 ["constrength"];
$flow = $result2 ["conflow"];
$range = ($result2 ["slumpmax"]-$result2 ["slumpmin"])/2;
$range2 = ($result2 ["airmax"]-$result2 ["airmin"])/2;
$air = $result2 ["airmax"]-$range2/2;
$chlomax = $result2 ["chlomax"];
$tempmax = $result2 ["contempmax"];
$tempmin = $result2 ["contempmin"];

};

// var_dump($range);

// ４．データ表示
$view_x = "<tr text-align='center' style='background: #BDD7EE;color:#833C0C;'><th width:14%;>種別</th><th  width:14%;>呼び強度</th><th width:14%;>フロー</th><th width:5%;>範囲</th><th width:14%;>空気量</th><th width:5%;>範囲</th><th width:14%;>塩化物</th><th width:10%;>Con温度Min</th><th width:10%;>Con温度MAX</th></tr>";
$view_y = "<tr style='background: #BDD7EE;color:#833C0C'><th width:20%;>通り芯符号</th><th width:20%;>間隔</th><th width:20%;>修正</th><th width:20%;>削除</th></tr>";





$view_x .= '<tr align="center"><td>'.$type.'</td>';
$view_x .= '<td>'.$strength.'</td>';
$view_x .= '<td>'.$flow.'</td>';
$view_x .= '<td>±'.$range.'</td>';
$view_x .= '<td>'.$air.'</td>';
$view_x .= '<td>±'.$range2.'</td>';
$view_x .= '<td>'.$chlomax.'以下</td>';
$view_x .= '<td>'.$tempmin.'℃以上</td>';
$view_x .= '<td>'.$tempmax.'℃以下</td>';
$view_x .= '</tr>';

// var_dump($id);


?>




<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<script src="js/jquery-2.1.3.min.js"></script>
<!-- <link rel="stylesheet" href="css/main.css" /> -->
<!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
<style>div{padding: 10px;font-size:16px;}</style>
<title>コンクリート仕様・プラント</title>
</head>
<body>

<header style="position: fixed;width:100%;z-index: 9999;">
  <nav class="navbar navbar-default" style="background:linear-gradient(to bottom, #8EA9DB 70%, #D9EAFF 100%); border-color:#305496;font-size:15px;display:flex;justify-content:space-between;">
    <div class="container-fluid"><p style="font-size: 20px;color:#E2EFDA;font-weight: bold;">CFTコンクリート施工管理システム</p></div>
    <div>
      <a class="navbar-brand" href="genba.php">現場選択</a>
      <a class="navbar-brand" href="sekkei.php">設計入力</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>

</header>


<p style="height:100px;"></p>
<p style="font-size:20px;">現場名：<?= $gen_name ?></p>
<h3>設計図書基準値</h3>
<form name="form1" action="constandard_act.php" method="post">
<div style="display: flex; justify-content:space-between; width:670px">

     <div style="display: flex; justify-content:center; margin:10px;width:270px;">  
        <p style="font-size:16px;width:100px margin:0px; padding:0px;">ｺﾝｸﾘｰﾄ種類：</p>
        <select id="Type" name="Type" style='color:white; border-color:#3b82f6;color:white; font-size:14px;margin:10px; background:#8EA9DB; border-radius:3px;width:100px; height:30px;'>
          <option value="N">－</option>
          <option value="F">普通コンクリート</option>
          <option value="H">高強度コンクリート</option>
          </select>
     </div> 
        
        <div style="display: flex; justify-content:center; margin:10px;width:200px;">  
          <p style="font-size:16px;width:100px margin:0px; padding:0px;">呼び強度：</p>
          
          <select id="Strength" name="Strength" style='color:white; border-color:#3b82f6;color:white; font-size:14px;margin:10px; background:#8EA9DB; border-radius:3px;width:60px; height:30px;'>
              <!-- Options2 will be added dynamically -->
          </select>
        </div> 


        <div style="display: flex; justify-content:center; margin:10px;width:200px;">  
          <p style="font-size:16px;width:100px margin:0px; padding:0px;">フロー値：</p>
          <select id="Flow" name="Flow" style='color: white; border-color: #3b82f6; color: white; font-size: 14px; margin:10px; background: #8EA9DB; border-radius: 3px; width: 60px; height:30px;'>
            <!-- Options will be added dynamically -->
          </select>

         </div>  
</div>
<p style="margin:0px;font-size:18px;">■設計図書基準値</p>
 ◆セメントの種類：　　<input id="Slump"  name="Slump" type="text" style="margin:10px; width:50px;" value="<?= $flow ?>"/>cm ±<input type="text" id="Span" name="Span" style="margin:10px; width:50px;"value="<?= $range ?>"/>cm<br>
 ◆設計基準強度Fc：　<input id="tempmin" name="Tempmin" type="text" style="margin:10px;width:50px;" value="<?= $tempmin ?>"/>℃以上<input type="text" name="Tempmax" style="margin:10px;width:50px;" value="<?= $tempmax ?>"/>℃以下<br>
 ◆スランプフロー：　　　　　　<input id="Air" name="Air" type="text" style="margin:10px; width:50px;" value="<?= $air ?>"/>±<input type="text" name="AirSpan" style="margin:10px; width:50px;" value="<?= $range2 ?>"/>％<br>
 ◆目標空気量：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 ◆単位水量：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 ◆水結合材比：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 ◆塩化物含有量：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 ◆ブリーディング量：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 ◆沈降量：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下
 <input style="margin:10px;" type="submit" value="登録" />
 

<p style="margin:0px;font-size:18px;">■プラント設定基準値</p>
 ◆プラント工場名：　　<input id="Slump"  name="Slump" type="text" style="margin:10px; width:50px;" value="<?= $flow ?>"/>cm ±<input type="text" id="Span" name="Span" style="margin:10px; width:50px;"value="<?= $range ?>"/>cm<br>
 ◆プラント所在地：　<input id="tempmin" name="Tempmin" type="text" style="margin:10px;width:50px;" value="<?= $tempmin ?>"/>℃以上<input type="text" name="Tempmax" style="margin:10px;width:50px;" value="<?= $tempmax ?>"/>℃以下<br>
 ◆想定運搬時間：　　　　　　<input id="Air" name="Air" type="text" style="margin:10px; width:50px;" value="<?= $air ?>"/>±<input type="text" name="AirSpan" style="margin:10px; width:50px;" value="<?= $range2 ?>"/>％<br>
 ◆設計基準強度：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下　　　　
 ◆スランプフロー：　　　　　　<input id="Air" name="Air" type="text" style="margin:10px; width:50px;" value="<?= $air ?>"/>±<input type="text" name="AirSpan" style="margin:10px; width:50px;" value="<?= $range2 ?>"/>％<br>
 ◆単位セメント量：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 ◆単位水量：　　　　　　<input id="Air" name="Air" type="text" style="margin:10px; width:50px;" value="<?= $air ?>"/>±<input type="text" name="AirSpan" style="margin:10px; width:50px;" value="<?= $range2 ?>"/>％<br>
 ◆水セメント比：　　　<input id="ChloMax" name="ChloMax" type="text" style="margin:10px; width:50px;" value="<?= $chlomax ?>"/>kg/m3以下<br>
 <input style="margin:10px;" type="submit" value="登録" />
 
</form>

<p style="margin:0px;">管理基準値登録値</p> 
<table style="font-size: 12px;width: 600px;">
 <?= $view_x ?>
</table>
 <p style="margin:0px;">コンクリート仕様・プラント設定値</p>
 <tabe style="font-size: 12px;width: 600px;">
 <?= $view_y ?>
 </table>

 

 <input type="button" onclick="location.href='./sekkei.php'" value="設計入力に戻る" style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>
 <input type="button" onclick="location.href='./menu2.php'" value="メニュー画面へ" style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>




 <script>
      // セレクトボックスの値取得
      const type = document.getElementById("Type");
      const str = document.getElementById("Strength");
      const flow = document.getElementById("Flow");
      const slump = document.getElementById("Slump");
      const span = document.getElementById("Span");

    //  呼び強度選択値取得
    type.addEventListener("change", function (e) {
        var category = type.value;
        console.log("Type value changed to:", category);
        let options2 = '';

        if(category == "F"){
          options2 = '<option value="27">27</option><option value="30">30</option><option value="33">33</option><option value="36">36</option><option value="40">40</option><option value="42">42</option><option value="45">45</option>';
        }else{
          options2 = '<option value="50">50</option><option value="55">55</option><option value="60">60</option>';
        }
        str.innerHTML = options2;
        console.log("Type options2 updated to:", options2);
      });

    //  呼び強度選択値取得
      str.addEventListener("change", function (e) {
        var strvalue = str.value;
        console.log("Strength value changed to:", strvalue);
        let options = '';

        if (strvalue == 27 || strvalue == 30) {
          options = '<option value="N">-</option><option value="45">45</option>';
        } else if (strvalue == 33) {
          options = '<option value="45">45</option><option value="50">50</option>';
        } else if (strvalue == 36) {
          options = '<option value="45">45</option><option value="50">50</option><option value="55">55</option>';
        } else {
          options = '<option value="45">45</option><option value="50">50</option><option value="55">55</option><option value="60">60</option>';
        }
        flow.innerHTML = options;
        console.log("Flow options updated to:", options);
      });

    // フロー値選択値取得
      flow.addEventListener("change", function (e) {
        var slumpvalue = flow.value;
        // console.log("Flow value changed to:", slumpvalue);
        slump.value = slumpvalue;
        console.log("Slump  value updated to:", slumpvalue);

        if(slumpvalue == 60){
          var spanwidth = 10;
        }else{
          var spanwidth = 7.5;
        }
        span.value = spanwidth;
        console.log("Span  value updated to:", spanwidth);
      });

  </script>


 </body>
</html>