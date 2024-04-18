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

// 0.図面最大値取得
$stmt = $pdo->prepare("SELECT MAX(coordX) AS `max_X` FROM design where gen_name = :gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();
foreach ($stmt as $row) {
  $max_X =$row['max_X'];
}

$stmt = $pdo->prepare("SELECT MAX(coordY) AS `max_Y` FROM design where gen_name = :gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();
foreach ($stmt as $row2) {
  $max_Y =$row2['max_Y'];
}



// 表示比率設定
$Xrate = 1100/$max_X/2;
$Yrate = 500/$max_Y;
var_dump($Xrate);
var_dump($Yrate);




// １．通り芯表示
$stmt = $pdo->prepare("SELECT * FROM stcore WHERE gen_name = :gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status2 = $stmt->execute();



// ２．通り芯図面表示
// 基準座標（0,0）設定
$Ref_x = 60;
$Ref_y = 530;


$view_core_x = "ctx.font = '16px Roboto medium';";
$view_coordineate = "ctx.font = '10px Roboto medium';";

$view_y10= $Ref_y+20;
$view_x10= $Ref_x-20;

$view_linex ='';
$view_liney ='';

if($status2==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
//Selectデータの数だけ自動でループしてくれる
//FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result2 = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    if($result2['axle']=='X'){
      $Ref_x2 = $Ref_x +$result2['coord']*$Xrate;
    }
    elseif($result2['axle']=='Y'){
      $Ref_y2 = $Ref_y -$result2['coord']*$Yrate;
    }
   }
  }





// ３．通り芯選択一覧表示（通り芯情報図示含む）
  $stmt = $pdo->prepare("SELECT * FROM stcore WHERE gen_name = :gen_name");
  $stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
  $status = $stmt->execute();
  
  
  
  if($status==false) {
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:".$error[2]);
  }else{
  
      while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
          //GETデータ送信リンク作成
          // <a>で囲う。
          if($result['axle']=='X'){
            // 入力情報表示_X
            $view_x .= '<tr><td>'.$result['stcore_num'].'</td>';
            $view_x .= '<td>'.$result['stcore_coordineate'].'</td>';
            $view_x .= '<td id="ss"><a href=core_ss.php?id='.$result['stcore_id'].'>修正</td>';
            $view_x .= '<td id="aa"><a href=core_aa.php?id='.$result['stcore_id'].'>削除</td>';
            $view_x .= '</tr>';
  
            // 通り芯符号表示_X
            $view_x10 = $view_x10+$result['stcore_coordineate']/1000*$Xrate;
            $view_x11 = $view_x10+10;
            $view_x12 = $view_x10-$result['stcore_coordineate']/2000*$Xrate;
            
         

            $view_core_x .= "ctx.fillText('".$result['stcore_num']."',".$view_x10.',590);';
            
            // 芯間距離表示
            $view_coordineate .= "ctx.fillText('".$result['stcore_coordineate']."',".$view_x12.',600);';
  
            // 通り芯表示
            $view_linex .='ctx.moveTo('.$view_x11.','.$view_y10.');';
            $view_linex .='ctx.lineTo('.$view_x11.','.$Ref_y2.');';
            
  
  
          }
          elseif($result['axle']=='Y'){
            // 入力情報表示_Y
            $view_y .= '<tr><td>'.$result['stcore_num'].'</td>';
            $view_y .= '<td>'.$result['stcore_coordineate'].'</td>';
            $view_y .= '<td id="ss"><a href=core_ss.php?id='.$result['stcore_id'].'>修正</td>';
            $view_y .= '<td id="aa"><a href=core_aa.php?id='.$result['stcore_id'].'>削除</td>';
            $view_y .= '</tr>';
            // 通り芯符号表示_Y
            $view_y10 = $view_y10-$result['stcore_coordineate']/1000*$Yrate;
            $view_y11 = $view_y10-10;
            $view_y12 = $view_y10+$result['stcore_coordineate']/2000*$Yrate;
            $view_core_x .= "ctx.fillText('".$result['stcore_num']."',5,".$view_y10.');';
  
            // 芯間距離表示_Y
            $view_coordineate .= "ctx.fillText('".$result['stcore_coordineate']."',0,".$view_y12.');';
  
            $view_liney .='ctx.moveTo('.$Ref_x2.','.$view_y11.');';
            $view_liney .='ctx.lineTo(40,'.$view_y11.');';
            
  
          }
      }
    }


  


// ５．杭符号選択情報抽出
$stmt = $pdo->prepare("select * from pillvirtispec where gen_name = :gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();

$select = "<select name='pill_sign' style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background-color:#8EA9DB; border-radius:5px; width:80px;'>";
$select .= "<br><option value=''>-</option>";

// 配列グループ作成
$pill_sign_a= array();
$pill_sign_b= "";

if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}
else{
  while( $result4 = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      
    // 前回の配列呼び出し
    $pill_sign_c = $pill_sign_b;
    // 配列に値を追加
    $pill_sign_a[] = $result4['pill_sign'];
    // 配列内の重複を削除
    $pill_sign_b = array_unique($pill_sign_a);

    if($pill_sign_c != $pill_sign_b ){
      $select .= '<option value="'.$result4['pill_sign'].'">'.$result4['pill_sign'].'</option>';
    }
  }
  $select .= '</select>';
}


// ６．柱情報一覧表抽出/図示
$stmt = $pdo->prepare("select * from design where gen_name = :gen_name");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$status = $stmt->execute();

// var_dump($status);


$shape_pilly= $Ref_y;
$shape_pillx= $Ref_x-20;

$shape_pill ="";
$view_pillnum ="";


if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}
else{
//Selectデータの数だけ自動でループしてくれる
//FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
      //GETデータ送信リンク作成
      // <a>で囲う。
       
     // 柱位置抽出
      $shape_pillx2 = $result['coordX']*$Xrate;
      $shape_pilly2 = $result['coordY']*$Yrate;
      $shape_pillx3 = $shape_pillx + $shape_pillx2;
      $shape_pilly3 = $shape_pilly - $shape_pilly2;
    // 柱番号用位置抽出
      $shape_pilly4 = $shape_pilly3 -3;
    // 柱符号用位置抽出
      $shape_pilly5 = $shape_pilly3 +33;
    
     // 柱表示
      $shape_pill .= 'ctx.rect(';
      $shape_pill .= $shape_pillx3.',';
      $shape_pill .= $shape_pilly3.',';
      $shape_pill .= '20,20);';

     //柱番号抽出 
      $shape_pillnum = $result['pill_num'];
      $view_pillnum .= "ctx.fillText('".$shape_pillnum."',".$shape_pillx3.",".$shape_pilly4.');';

     //柱符号抽出 
      $shape_pillsign = $result['pill_sign'];
      $view_pillsign .= "ctx.fillText('".$shape_pillsign."',".$shape_pillx3.",".$shape_pilly5.');';
   }
  }
  
var_dump($shape_pillx3);


?>




<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
<title>柱設定入力</title>
</head>
<body>

<header style="position: fixed;width:100%;z-index: 9999;">
  <nav class="navbar navbar-default" style="background:linear-gradient(to bottom, #8EA9DB 70%, #D9EAFF 100%); border-color:#305496;font-size:15px;display:flex;justify-content:space-between;">
    <div class="container-fluid"><p style="font-size: 24px;color:#E2EFDA;font-weight: bold;">CFTコンクリート施工管理システム</p></div>
    <div>
      <a  href="login.php">TOPページ</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>
</header>



<p style="height:100px;"></p>
<div>
<p style="font-size:20px;">現場名：<?= $gen_name ?></p>
<div style="display: flex; justify-content:flex-start;margin:5px;">
<h3>柱選択画面</h3>
<input type="button" onclick="location.href='./sekkei.php'" value="設計入力に戻る" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>
<input type="button" onclick="location.href='./time.php'" value="施工管理へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>
</div>
</div>


 <canvas id="core" width="1100" height="600"></canvas>


<!--/ コンテンツ表示画面 -->
<script>

// Canbas記載事項
    // plane記載
    function init() {
        var canvas = document.getElementById("core");
        // 表示位置指定
          canvas.style.position = "absolute";
          canvas.style.left = "30px";
          canvas.style.top = "250px";
        var ctx = canvas.getContext("2d");
        // 通り芯番号表示
        ctx.font = '16px Roboto medium';
        // ctx.fillText('柱選択', 10, 20);
        <?= $view_core_x ?>
        // 芯間距離表示
        <?= $view_coordineate ?>
       
        // 通り芯線表示
        ctx.lineWidth = 3;
        ctx.strokeStyle = "#595959";
        // X軸
        <?= $view_linex ?>
        // Y軸
        <?= $view_liney ?>
        ctx.stroke();
        // 柱表示枠線のみ
        ctx.beginPath();
          <?= $shape_pill ?>;
          ctx.strokeStyle = 'red';
          ctx.lineWidth = 3;
         ctx.stroke();

         ctx.beginPath();
          ctx.fillStyle = 'blue';
          ctx.lineWidth = 1;
          <?= $view_pillnum ?>;
          <?= $view_pillsign ?>;
         ctx.stroke();

        draw();

        }
      

// コンテンツ描画処理
          window.onload = function() {
              init();
          }

// マウスオーバーイベント！！！！
var targetFlag = false; // trueでマウスが要素に乗っているとみなす
var rect = null;

/* Canvas上にマウスが乗った時 */
function onMouseOver(e) {
    rect = e.target.getBoundingClientRect();
    canvas.addEventListener('mousemove', onMouseMove, false);
}
/* Canvasからマウスが離れた時 */
function onMouseOut() {
    canvas.removeEventListener('mousemove', onMouseMove, false);
}
/* Canvas上でマウスが動いている時 */
function onMouseMove(e) {
    /* マウスが動く度に要素上に乗っているかかどうかをチェック */
    moveActions.updateTargetFlag(e);

    /* 実行する関数には、間引きを噛ませる */
    if (targetFlag) {
        moveActions.throttle(moveActions.over, 50);
    } else {
        moveActions.throttle(moveActions.out, 50);
    }
}

/* mouseMoveで実行する関数 */
var moveActions = {
    timer: null,
    /* targetFlagの更新 */
    updateTargetFlag: function(e) {
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;

        /* 最後の50は、該当する要素の半サイズを想定 */
        var a = (x > w / 2 - 50);
        var b = (x < w / 2 + 50);
        var c = (y > h / 2 - 50);
        var d = (y < h / 2 + 50);

        targetFlag = (a && b && c && d); // booleanを代入
    },
    /* 連続イベントの間引き */
    throttle: function(targetFunc, time) {
        var _time = time || 100;
        clearTimeout(this.timer);
        this.timer = setTimeout(function () {
            targetFunc();
        }, _time);
    },
    out: function() {
        draw();
    },
    over: function() {
        drawIsHover();
    }
};

function draw(color) {
    // デフォルトもしくはマウスが要素から離れた時の描画処理
}
function drawIsHover() {
    // マウスが要素に乗った時の描画処理
}

canvas.addEventListener('mouseover', onMouseOver, false);
canvas.addEventListener('mouseout', onMouseOut, false);

draw();


</script>




</body>
</html>

