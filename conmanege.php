<?php
// SESSIONスタート
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$gen_name = $_SESSION['gen_name'];

require_once('funcs.php');
// ログインチェック
// loginCheck();
$pdo = db_conn();

// 登録情報検索
$stmt = $pdo->prepare("SELECT COUNT(*) FROM constmanege2 WHERE gen_name=:gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
  sql_error($status);
} else {
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

$status22 = $result["COUNT(*)"];
$_SESSION["status22"] = $status22;
var_dump($status22);

// 登録情報検索2
$stmt = $pdo->prepare("SELECT COUNT(*) FROM plant WHERE gen_name=:gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
  sql_error($status);
} else {
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
}

$status33 = $result["COUNT(*)"];
$_SESSION["status33"] = $status22;
var_dump($status33);








// ４．データ表示
$view_x = "<tr text-align='center' style='background: #BDD7EE;color:#833C0C;'><th width='14%'>種類</th><th width='14%'>Fc強度</th><th width='14%'>フロー</th><th width='5%'>空気量</th><th width='14%'>単位水量</th><th width='5%'>水結合材比</th><th width='14%'>塩化物</th><th width='10%'>ﾌﾞﾘｰﾃﾞｨﾝｸﾞ量</th><th width='10%'>沈下量</th></tr>";
$view_y = "<tr style='background: #BDD7EE;color:#833C0C'><th width='20%'>通り芯符号</th><th width='20%'>間隔</th><th width='20%'>修正</th><th width='20%'>削除</th></tr>";
$type = "";
$strength = "";
$slump = "";
$air = "";
$water = "";
$ww = "";
$chlo = "";
$bb = "";
$sink = "";

if ($status22 != 0) {
    $stmt2 = $pdo->prepare("SELECT * from constmanege2 where gen_name=:gen_name");
    $stmt2->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
    $status2 = $stmt2->execute();
    
    if ($status2 == false) {
      sql_error($status2);
    } else {
      while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $type = $result2["design_contype"];
        $strength = $result2["design_strength"];
        $slump = $result2["design_slump"];
        $air = $result2["design_air"];
        $water = $result2["design_water"];
        $ww = $result2["design_ww"];
        $chlo = $result2["design_chlo"];
        $bb = $result2["design_bb"];
        $sink = $result2["design_sink"];
        
        // ４.２データ表示
        $view_x .= '<tr align="center"><td>' . $type . '</td>';
        $view_x .= '<td>' . $strength . '</td>';
        $view_x .= '<td>' . $slump . '</td>';
        $view_x .= '<td>' . $air . '</td>';
        $view_x .= '<td>' . $water . '</td>';
        $view_x .= '<td>' . $ww . '</td>';
        $view_x .= '<td>' . $chlo . '以下</td>';
        $view_x .= '<td>' . $bb . '℃以上</td>';
        $view_x .= '<td>' . $sink . '℃以下</td>';
        $view_x .= '</tr>';
      }
    }
};
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

<header style="position: fixed;width:100%;z-index: 9999;top: 0;left: 0;margin: 0;padding: 0;">
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


<p style="height:60px;"></p>
<p style="font-size:20px;">現場名：<?= $gen_name ?></p>
<h3>設計図書基準値・プラント設定基準値</h3>
<div style="display: flex; justify-content:flex-start;margin:5px;">
    <div style="width:450px;">
        <p style="margin:0px;font-size:18px;">■設計図書基準値</p>
        <form name="form1" action="conmanege_act.php" method="post" style="background-color: #DDEBF7;">
            ◆セメントの種類：　<input id="Type"  name="Type" type="text" style="margin:10px; width:220px;" value="<?= $type ?>"/><br>
            ◆設計基準強度Fc：　<input id="Strength" name="Strength" type="text" style="margin:10px;width:50px;" value="<?= $strength ?>"/>N/mm2<br>
            ◆スランプフロー：　<input id="Slump" name="Slump" type="text" style="margin:10px; width:50px;" value="<?= $slump ?>"/>cm<br>
            ◆目標空気量：　　　<input id="Air" name="Air" type="text" style="margin:10px; width:50px;" value="<?= $air ?>"/>%<br>
            ◆単位水量：　　　　<input id="Water" name="Water" type="text" style="margin:10px; width:50px;" value="<?= $water ?>"/>kg/cm3以下<br>
            ◆水結合材比：　　　<input id="Ww" name="Ww" type="text" style="margin:10px; width:50px;" value="<?= $ww ?>"/>%以下<br>
            ◆塩化物含有量：　　<input id="Chlo" name="Chlo" type="text" style="margin:10px; width:50px;" value="<?= $chlo ?>"/>kg/m3以下<br>
            ◆ブリーディング量：<input id="Bb" name="Bb" type="text" style="margin:10px; width:50px;" value="<?= $bb ?>"/>cm3/cm2以下<br>
            ◆沈　降　量：　　　<input id="Sink" name="Sink" type="text" style="margin:10px; width:50px;" value="<?= $sink ?>"/>mm以下　　　
            <input style="margin:10px;font-size:16px;" type="submit" value="登録" />
        </form>
    </div>
 
    <div style="width:450px;">
        <p style="margin:0px;font-size:18px;">■プラント設定基準値</p>
        <form name="form1" action="plant_act.php" method="post" style="background-color: #DDEBF7;">
            ◆プラント工場名：　<input id="Plant"  name="Plant" type="text" style="margin:10px; width:200px;" value="<?= $plant ?>"/><br>
            ◆プラント所在地：　<input id="Address" name="Address" type="text" style="margin:10px;width:250px;" value="<?= $address ?>"/><br>
            ◆想定運搬時間：　　<input id="Time" name="Time" type="Time" style="margin:10px; width:50px;" value="<?= $air ?>"/>分<input type="text" name="Distance" style="margin:10px; width:50px;" value="<?= $range2 ?>"/>km<br>
            ◆設計基準強度Fc：　<input id="Strength" name="Strength" type="text" style="margin:10px; width:50px;" value="<?= $design_strength ?>"/>N/mm2<br>
            ◆スランプフロー：　<input id="Slump" name="Slump" type="text" style="margin:10px; width:50px;" value="<?= $design_slump ?>"/>cm<br>
            ◆配合（呼び名）：　<input id="Mix" name="Mix" type="text" style="margin:10px; width:50px;" value="<?= $mix ?>"/><br>
            ◆単位セメント量：　<input id="Ceme" name="Ceme" type="text" style="margin:10px; width:50px;" value="<?= $ceme ?>"/>kg/m3<br>
            ◆単位水量：　　　　<input id="Water" name="Water" type="text" style="margin:10px; width:50px;" value="<?= $water ?>"/>kg/cm3<br>
            ◆水セメント比：　　<input id="WCrate" name="WCrate" type="text" style="margin:10px; width:50px;" value="<?= $wcrate ?>"/>　　　
            <input style="margin:10px;font-size:16px;" type="submit" value="登録" />
        </form>
    </div>
</div>

<p style="margin:0px;">コンクリート設計仕様・プラント設定値</p> 
<table style="font-size: 12px;width: 600px;">
 <?= $view_x ?>
</table>
 <p style="margin:0px;">管理基準値登録値</p>
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