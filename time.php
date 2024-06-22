<?php

//SESSIONスタート
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$gen_name = $_SESSION['gen_name'];

$pill_num = "No.1";

// var_dump($gen_name);
require_once('funcs.php');
//ログインチェック
// loginCheck();
$pdo = db_conn();

// 現場経歴一覧取得_emp_idで抽出
// header("Refresh:5");
// echo date('H:i:s Y-m-d');



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

var_dump($pill_sign);



// ５．ボタン記述
$stmt = $pdo->prepare("select * from pillvirtispec where gen_name = :gen_name and pill_sign = :pill_sign");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$stmt->bindValue(':pill_sign', $pill_sign, PDO::PARAM_STR);

$status = $stmt->execute();

$button = "";

if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}
else{
//Selectデータの数だけ自動でループしてくれる
//FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $button .='<form name="form'.$result["id"].'" action="time_act.php" method="post" style="font-size:14px;width:800px;">';
    $button .='<div style="display: flex; justify-content:flex-start;margin:5px;">';
    $button .='<p style="font-size:20px;margin:10px;width:150px;">';
    $button .=$result['floor_num'];
    $button .='打設完了：</p>';
    $button .='<input type="hidden" name="gen_name" value="'.$gen_name.'"/>';
    $button .='<input type="hidden" name="pill_num" value="'.$pill_num.'"/>';
    $button .='<input type="hidden" name="floor_num" value="'.$result["floor_num"].'"/>';
    $button .=' <input style="margin:10px;" type="submit" id="'.$result['floor_num'].'"' .'value="'.$result['floor_num'].'打設完了"/>';

    $button .='<div style="font-size:20px;margin-left:30px;" id="time'.$result['floor_num'];
    $button .='">00:00.000</div>';
    $button .='</div></form>';
// onclick="disabled = true;"1クリックで押せなくなる
  }
}


// ６．グラフ表示
$stmt = $pdo->prepare("select * from speed where gen_name = :gen_name and pill_num = :pill_num");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$stmt->bindValue(':pill_num', $pill_num, PDO::PARAM_STR);

$status = $stmt->execute();

$kaidaka = "";
$rgtime = "";
$rgtime3 = null;



if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}
else{
//Selectデータの数だけ自動でループしてくれる
//FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){ 
    $kaidaka .=$result["floor_num"].',';
 

    $rgtime2 = strtotime($result["rgtime"])- $rgtime3;
    $rgtime3 = $rgtime2;

    $kaidaka .=$rgtime2.',';
  }
}

var_dump($kaidaka);



?>




<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/sample.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<style>div{padding: 10px;font-size:16px;}</style>
<title>打上高さ管理</title>
</head>
<body>

<header style="position: fixed;width:100%;z-index: 9999;">
  <nav class="navbar navbar-default" style="background:linear-gradient(to bottom, #8EA9DB 70%, #D9EAFF 100%); border-color:#305496;font-size:15px;display:flex;justify-content:space-between;">
    <div class="container-fluid"><p style="font-size: 20px;color:#E2EFDA;font-weight: bold;">CFTコンクリート施工管理システム</p></div>
    <div>
      <a class="navbar-brand" href="genba.php">現場選択</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>
</header>



<p style="height:100px;"></p>

<p style="font-size:20px;">現場名：<?= $gen_name ?></p>

<h1>Stopwatch⌚️</h1>
  <div id="container">
    <div id="time">00:00.000</div>
    <div id="buttons">
      <input id="start2" type="button" value="start">
      <input id="stop" type="button" value="stop">
      <input id="reset" type="button" value="reset">
    </div>
  </div>  
  <!-- <script src='main.js'></script> -->



<h3>打上り高さ管理</h3>
<div style="display: flex; justify-content:space-around;margin:5px;width:300px;">
<p style="font-size:16px; margin:3px;">杭番号：<?= $pill_num ?></p>
<p style="font-size:16px; margin:3px;">杭符号：<?= $pill_sign ?></p>
</div>
<div style="display: flex; justify-content:space-around;margin:5px;width:300px;">
<p style="font-size:16px; margin:3px;">位置：<?=  $stcore_numX ?> － <?=  $stcore_numY ?></p>
<p style="font-size:16px; margin:3px;">柱長：<?= $virtilength ?></p>
</div>


<div style="display: flex; justify-content:flex-start;margin:5px;width:800px;">
<form name="form1" action="time_act.php" method="post" style="font-size:14px;">
<div style="display: flex; justify-content:flex-start;margin:5px;">
 <p style="font-size:20px;margin:10px;width:150px;">打設開始：</p>
 <input type="hidden" name="gen_name" value="<?= $gen_name ?>"/>
 <input type="hidden" name="pill_num" value="<?= $pill_num ?>"/>
 <input type="hidden" name="floor_num" value="打設開始"/>
 <input style="margin:10px;" id="start" type="submit" onclick="disabled = true" value="打設　開始" />
 </div>
 </form>

 <form name="form1" action="time_aa.php" method="post" style="font-size:14px;">
<div style="display: flex; justify-content:flex-start;margin:5px;">
 <input type="hidden" name="gen_name" value="<?= $gen_name ?>"/>
 <input type="hidden" name="pill_num" value="<?= $pill_num ?>"/>
 <input type="hidden" name="floor_num" value="打設開始"/>
 <input style="margin:10px;" type="submit" name="リセット" value="リセット" />
 </div>
 </form>

 </div>
 
 <form name="form1" action="time_act.php" method="post" style="font-size:14px;width:800px;">
 <div style="display: flex; justify-content:flex-start;margin:5px;">
    <p style="font-size:20px;margin:10px;width:150px;">一時停止：</p>
    <input type="hidden" name="gen_name" value="<?= $gen_name ?>"/>
    <input type="hidden" name="pill_num" value="<?= $pill_num ?>"/> 
    <input type="hidden" name="floor_num" value="一時停止"/>
    <div class="toggle">
      <input type="checkbox" value="一時　停止" name="check"/>
    </div>
 </div>
 </form>




 <?= $button ?>

<!-- 折れ線グラフ -->
<div style="width:500px;" >
    <canvas id="chart"></canvas>
   </div>

<!-- グラフ表示位置変更 -->
<script type="text/javascript">
      var canvas;
      var ctx;

      function init() {
          canvas = document.getElementById("chart");
          canvas.style.position = "absolute";
          canvas.style.right = "80px";
          canvas.style.top = "260px";
          ctx = canvas.getContext("2d");
          
          draw();
      }

      function draw() {
          ctx.style = "#000000";
          ctx.rect( 0, 0, 100, 100 );
          ctx.stroke();
      }

      window.onload = function() {
          init();
      };


<!-- グラフ表示設定 -->

        var ctx = document.getElementById("chart");
        var myLineChart = new Chart(ctx, {
          // グラフの種類：折れ線グラフを指定
          type: 'line',
          data: {
            // x軸の各メモリ
            labels: [],
            datasets: [
              {
                label: '打上り完了時間',
                data: [],
                borderColor: "#ea2260",
                lineTension: 0, //<===追加
                fill: true, 
                backgroundColor: "#00000000"
                          
              },
            ],
          },
          options: {
            title: {
              display: true,
              text: '打上り高さ管理'
            },
            scales: {
              yAxes: [{
                        // type: 'time',
                        // distribution: 'series'
                        ticks: {
                          suggestedMax: 100,
                          suggestedMin: 50,
                          stepSize: 10,  // 縦メモリのステップ数
                          callback: function(value, index, values){

                    return  value +  'sec'  // 各メモリのステップごとの表記（valueは各ステップの値）
                  }
                }
              }]
            },
          }
        });

// トグルスイッチのJQuery

  $(".toggle").on("click", function() {
  $(".toggle").toggleClass("checked");
  if(!$('input[name="check"]').prop("checked")) {
    $(".toggle input").prop("checked", true);
  } else {
    $(".toggle input").prop("checked", false);
  }
});


// ストップウオッチのJS
const time = document.getElementById('time1F');

const startButton = document.getElementById('start');
// const startButton2 = document.getElementById('1F');
// const startButton3 = document.getElementById('2F');
const stopButton = document.getElementById('1F');
const stopButton2 = document.getElementById('2F');
const stopButton3 = document.getElementById('3F');
const resetButton = document.getElementById('reset');

// 開始時間
let startTime;
// 停止時間
let stopTime = 0;
// タイムアウトID
let timeoutID;

// 時間を表示する関数
function displayTime() {
  const currentTime = new Date(Date.now() - startTime + stopTime);
  const h = String(currentTime.getHours()-1).padStart(2, '0');
  const m = String(currentTime.getMinutes()).padStart(2, '0');
  const s = String(currentTime.getSeconds()).padStart(2, '0');
  const ms = String(currentTime.getMilliseconds()).padStart(3, '0');

  time.textContent = `経過時間：${m}:${s}`;
  timeoutID = setTimeout(displayTime, 10);
}

// スタートボタンがクリックされたら時間を進める
startButton.addEventListener('click', () => {
  startButton.disabled = true;
  stopButton.disabled = false;
  resetButton.disabled = true;
  startTime = Date.now();
  displayTime();
});

startButton2.addEventListener('click', () => {
  startButton.disabled = true;
  stopButton.disabled = false;
  resetButton.disabled = true;
  startTime = Date.now();
  displayTime();
});

startButton3.addEventListener('click', () => {
  startButton.disabled = true;
  stopButton.disabled = false;
  resetButton.disabled = true;
  startTime = Date.now();
  displayTime();
});

// ストップボタンがクリックされたら時間を止める
stopButton.addEventListener('click', function() {
  startButton.disabled = false;
  stopButton.disabled = true;
  resetButton.disabled = false;
  clearTimeout(timeoutID);
  stopTime += (Date.now() - startTime);
});

stopButton2.addEventListener('click', function() {
  startButton.disabled = false;
  stopButton.disabled = true;
  resetButton.disabled = false;
  clearTimeout(timeoutID);
  stopTime += (Date.now() - startTime);
});

stopButton3.addEventListener('click', function() {
  startButton.disabled = false;

  stopButton.disabled = true;
  resetButton.disabled = false;
  clearTimeout(timeoutID);
  stopTime += (Date.now() - startTime);
});

// リセットボタンがクリックされたら時間を0に戻す
resetButton.addEventListener('click', function() {
  startButton.disabled = false;
  stopButton.disabled = true;
  resetButton.disabled = true;
  time.textContent = '00:00.000';
  stopTime = 0;
});

// ストップウオッチのJSここまで

  </script>

<!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"> -->


<!-- </script> -->




</body>
</html>