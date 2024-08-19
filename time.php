<?php

//SESSIONスタート
session_start();
$userid = $_SESSION['userid'];
$username = $_SESSION['username'];
$gen_name = $_SESSION['gen_name'];
$pill_num = $_SESSION["pill_num"];





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



// ５．ボタン記述
$stmt = $pdo->prepare("SELECT * FROM pillvirtispec WHERE gen_name = :gen_name AND pill_sign = :pill_sign ");
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
$stmt->bindValue(':pill_sign', $pill_sign, PDO::PARAM_STR);

$status = $stmt->execute();

$button = '';
$variable = '';
$start_time = '';
$stop_time = '';
$button_def = '';
$time22 = '';
$form22 = '';
$timeoutID = '';
$n_1 = "0F";
$cs_area =array();
$floor_height =array();
$labels = "labels: [";
$gr_data = "data:[";
$gr_data_plan = "data:[";

if ($status == false) {
    // execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:" . $error[2]);
} else {
    // Selectデータの数だけ自動でループしてくれる
    // FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
      //  button記載
        $button .= "<div style='display: flex; justify-content:flex-start;margin:5px;'>\n";
        $button .= "<p style='font-size:20px;margin:10px;width:150px;'>";
        $button .= $result['floor_num'];
        $button .= "打設完了：</p>\n";
        $button .= "<form id='form".$result['floor_num']."' action='time_act.php' method='post'>\n";
        $button .= '<input type="hidden" name="gen_name" value="' . $gen_name . '"/>';
        $button .= '<input type="hidden" name="pill_num" value="' . $pill_num . '"/>';
        $button .= '<input type="hidden" name="floor_num" value="' . $result["floor_num"] . '"/>';
        $button .= '<input type="hidden" name="measure_height" value="' . $result["floor_height"] . '"/>';
        $button .= '<input style="margin:10px;" type="submit" id="startButton' . $result['floor_num'] . '" value="' . $result['floor_num'] . '打設完了" disabled></form>';
        $button .= '<div style="font-size:20px;margin-left:30px;" id="time' . $result['floor_num'] . '">00:00.000</div>';
        $button .= '</div>';

      // JS_startbuttonの変数定義の記載
        $variable .= "const time".$result['floor_num']." = document.getElementById('time".$result['floor_num']."');\n";
        $variable .= "const startButton".$result['floor_num']." = document.getElementById('startButton".$n_1."');\n";
        $variable .= "const stopButton".$result['floor_num']." = document.getElementById('startButton".$result['floor_num']."');\n";
        $variable .= "const form".$result['floor_num']." = document.getElementById('form".$result['floor_num']."');\n";
        
      // ８．１グラフのラベル表示
        $labels .= "'".$result['floor_num']."',";





      // JS_starttimeの変数定義の記載
        $start_time .= "let startTime".$result['floor_num']."= 0;\n";

      // JS_stoptimeの変数定義の記載
        $stop_time .= "let stopTime".$result['floor_num']." = 0;\n";

        $timeoutID .= "let timeoutID".$result['floor_num'].";\n";


                      



      // 時間を表示する関数
      // $time22 .= "function displayTime".$result['floor_num']."() {
      // let elapsed;
      // let currentTime".$result['floor_num']."; 
      //   if(stopTime".$result['floor_num']."!=0){
      //     // 経過時間を計算する場合
      //      elapsed = stopTime".$result['floor_num']." - startTime".$result['floor_num'].";
      //      currentTime".$result['floor_num']." = new Date(elapsed); // 経過時間を基にDateオブジェクトを作成
      // } else {
      //     // 現在の時間とスタート時間の差を計算する場合
      //      elapsed = Date.now() - startTime".$result['floor_num'].";
      //      currentTime".$result['floor_num']." = new Date(elapsed); // 経過時間を基にDateオブジェクトを作成
      // }
  
      //   const h".$result['floor_num']." = String(currentTime".$result['floor_num'].".getHours()).padStart(2, '0');
      //   const m".$result['floor_num']." = String(currentTime".$result['floor_num'].".getMinutes()).padStart(2, '0');
      //   const s".$result['floor_num']." = String(currentTime".$result['floor_num'].".getSeconds()).padStart(2, '0');
      //   const ms".$result['floor_num']." = String(currentTime".$result['floor_num'].".getMilliseconds()).padStart(3, '0');
      //    if (time".$result['floor_num'].") {
      //   time".$result['floor_num'].".textContent = '経過時間：' + m".$result['floor_num']." + ':' + s".$result['floor_num']."; 
      //   }
      //   timeoutID".$result['floor_num']." = setTimeout(displayTime".$result['floor_num'].", 10)};\n";

      $time22 .="function displayTime".$result['floor_num']."() {
                  let elapsed; // 経過時間をミリ秒で格納
                  if (stopTime".$result['floor_num']." != 0) {
                      // 経過時間を計算する場合
                      elapsed = stopTime".$result['floor_num']." - startTime".$result['floor_num'].";
                  } else {
                      // 現在の時間とスタート時間の差を計算する場合
                      elapsed = Date.now() - startTime".$result['floor_num'].";
                  }

                  // ミリ秒を時間、分、秒に変換
                  const minutes = Math.floor(elapsed / 60000); // ミリ秒を分に変換
                  const seconds = Math.floor((elapsed % 60000) / 1000); // ミリ秒を秒に変換
                  const milliseconds = Math.floor((elapsed % 1000) / 10); // ミリ秒を10ms単位で切り捨て

                  const m".$result['floor_num']." = String(minutes).padStart(2, '0');
                  const s".$result['floor_num']." = String(seconds).padStart(2, '0');
                  const ms".$result['floor_num']." = String(milliseconds).padStart(2, '0');

                  if (time".$result['floor_num'].") {
                      time".$result['floor_num'].".textContent = '経過時間：' + m".$result['floor_num']." + ':' + s".$result['floor_num']." + ':' + ms".$result['floor_num'].";
                  }

                  timeoutID".$result['floor_num']." = setTimeout(displayTime".$result['floor_num'].", 10);
          }\n";

  
  







    //  formの定義関数
        $form22 .=  "startButton".$result['floor_num'].".onclick = function(event) {
                event.preventDefault(); // デフォルトのフォーム送信を防ぐ
                const form = document.getElementById('form".$n_1."');
                const formData = new FormData(form);
                const action = form.getAttribute('action');
                fetch(action, {
                    method: 'POST',
                    body: formData,
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(data => {
                        // タイマー開始
                        startButton".$result['floor_num'].".disabled = true;
                        stopButton".$result['floor_num'].".disabled = false;
                        startTime".$result['floor_num']." = Date.now();
                        displayTime".$result['floor_num']."();
                    })
                   .then(data => {
                       console.log('フォーム送信成功:', data); // デバッグ用のログ
                    // フォーム送信完了後にリロード
                    location.reload();// ページをリロードする
                     })
                    .catch(error => {
                        console.error('エラー:', error);
                    })
            };";



      // start・stopbuttonの定義の記載
        // $button_def .= "startButton".$result['floor_num'].".addEventListener('click', () => {
        //               //  startButton".$result['floor_num'].".disabled = true;
        //               //  stopButton".$result['floor_num'].".disabled = false;
        //               //  resetButton.disabled = true;
        //                startTime".$result['floor_num']." = Date.now();
        //                displayTime".$result['floor_num']."();});\n";

        // $button_def .= "stopButton".$result['floor_num'].".addEventListener('click', function() {
        //                 clearTimeout(timeoutID".$result['floor_num'].");
        //                 stopTime".$result['floor_num']." += (Date.now() - startTime);
        //                 document.getElementById('edit_area').innerHTML = stopTime".$result['floor_num']." ;})\n";

        // 1flor下の定義
        $n_1 = $result['floor_num'];




  
       

            // 打設数量算出
            $floor_height[] = $result['floor_height'];
            $stmt222 = $pdo->prepare("SELECT cs_area FROM pillcrossspec INNER JOIN pillvirtispec ON pillcrossspec.pill_crossnum = pillvirtispec.pill_crossnum WHERE pillvirtispec.pill_crossnum = :pill_crossnum && pillvirtispec.floor_num = :floor_num ");
            $stmt222->bindValue(':pill_crossnum', $result['pill_crossnum'], PDO::PARAM_STR);
            $stmt222->bindValue(':floor_num', $result['floor_num'], PDO::PARAM_STR);
            $stmt222->execute();
                      foreach ($stmt222 as $row222) {
                              $cs_area[] = $row222['cs_area']; 

                          }

         $floor_numaa[] = $result['floor_num'];   
    }

    // var_dump($floor_numaa);
    // var_dump($cs_area);
    $total = 0;
    $plan11 = 0;
     
// 各階の打設数量計算
    for ($i = 0; $i < count($floor_height); $i++) {
              ${"floorvol". ($i + 1)} = $cs_area[$i] * $floor_height[$i]/1000;
              $plan111 = $floor_height[$i]/1000;
              $total += ${"floorvol" . ($i + 1)}; 
              // グラフ計画時間
               $plan11 += $plan111;
               $gr_data_plan .="'".$plan11."',";
        }
      }

        //  formの定義関数(最終階の終了ボタン定義）
        $form23 =  "stopButton".$n_1.".onclick = function(event) {
          event.preventDefault(); // デフォルトのフォーム送信を防ぐ
          const form = document.getElementById('form".$n_1."');
          const formData = new FormData(form);
          const action = form.getAttribute('action');
          fetch(action, {
              method: 'POST',
              body: formData,
          })
              .then(response => {
                  if (!response.ok) {
                      throw new Error('Network response was not ok');
                  }
                  return response.text();
              })
              .catch(error => {
                  console.error('エラー:', error);
              })
        };\n";








                  // ７．サーバ登録情報検索
                  $search_floornum=array();
                  $search_rgtime=array();

                  $stmt3 = $pdo->prepare("select * from speed where gen_name = :gen_name and pill_num = :pill_num");
                  $stmt3->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);
                  $stmt3->bindValue(':pill_num', $pill_num, PDO::PARAM_STR);

                  $status3 = $stmt3->execute();
                  if($status3==false) {
                    // execute（SQL実行時にエラーがある場合）
                    $error3 = $stmt3->errorInfo();
                    exit("ErrorQuery:".$error3[2]);
                  }
                  else{
                    while ($result3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {

                    $search_floornum[] = $result3['floor_num'];
                    $search_rgtime[] = $result3['rgtime'];
                    }
                  }

                  $floorNum = count($search_floornum);
                  $floorNum_1 = $floorNum -1;
                  // var_dump($floorNum);           
                  // var_dump($search_rgtime);

                  // ８．２グラフ表示値
                  $i = 1;
                  if(!empty($search_rgtime)){
                    while($i !== $floorNum){ 
                      $rawdata1 = new DateTime($search_rgtime[$i]);
                      $rawdata0 = new DateTime($search_rgtime[0]);
                      $interval = $rawdata1->diff($rawdata0);
                      $minutes = ($interval->h * 60) + $interval->i + ($interval->s / 60);
                      $gr_data .= "'".$minutes."',";
                      $i++; 
                    }
                  }

                  $stop_time2 ="";
                  $startButton_on ="";
        
        
                  if ($floorNum != 0) {
                    $i = 0;
                    $j = 1;
                   
                
                    while ($i !== $floorNum) { 
                        if ($search_floornum[$i] === '打設開始') {
                            $start_time .="startTime1F = '".$search_rgtime[$i]."';\n";
                            $start_time .="function parseTimeString(timeString) {
                              const [hours, minutes, seconds] = timeString.split(':').map(Number);
                              const date = new Date();
                              date.setHours(hours);
                              date.setMinutes(minutes);
                              date.setSeconds(seconds);
                              date.setMilliseconds(0); // ミリ秒を0に設定
                              return date;
                          }\n
                          startTime1F = parseTimeString('".$search_rgtime[$i]."');\n"; // 文字列をDateに変換

                            $startButton_on .= "displayTime_total();\n";
                            $startButton_on .= "startButton0F.disabled = true;\n";
                            $startButton_on .= "stopButton1F.disabled = false;\n";
                        } else if ($search_floornum[$i] === '10F') {
                            $stop_time2 .= "stopTime".$search_floornum[$i]." = '".$search_rgtime[$i]."';\n";
                            // $start_time .= "document.getElementById('startButton".$search_floornum[$i]."').click();\n";
                        } else {
                            $start_time .= "startTime".$floor_numaa[$i]." = '".$search_rgtime[$i]."';\n";
                            $start_time .="function parseTimeString(timeString) {
                              const [hours, minutes, seconds] = timeString.split(':').map(Number);
                              const date = new Date();
                              date.setHours(hours);
                              date.setMinutes(minutes);
                              date.setSeconds(seconds);
                              date.setMilliseconds(0); // ミリ秒を0に設定
                              return date;
                          }\n
                            startTime".$floor_numaa[$i-1]." = parseTimeString('".$search_rgtime[$i]."');\n"; // 文字列をDateに変換

                            $stop_time2 .= "stopTime".$floor_numaa[$i-1]." = '".$search_rgtime[$i]."';\n";
                            $stop_time2 .="function parseTimeString(timeString) {
                              const [hours, minutes, seconds] = timeString.split(':').map(Number);
                              const date = new Date();
                              date.setHours(hours);
                              date.setMinutes(minutes);
                              date.setSeconds(seconds);
                              date.setMilliseconds(0); // ミリ秒を0に設定
                              return date;
                          }\n
                            stopTime".$floor_numaa[$i-1]." = parseTimeString('".$search_rgtime[$i]."');\n ";// 文字列をDateに変換
                            
                            // $startButton_on .= "document.getElementById('stopButton".$floor_numaa[$i]."').click();\n";

                            $startButton_on .= "displayTime".$floor_numaa[$i-1]."();\n";
                            $startButton_on .= "displayTime".$floor_numaa[$i]."();\n";
                            $startButton_on .= "startButton".$floor_numaa[$i].".disabled = true;\n";
                            $startButton_on .= "stopButton".$floor_numaa[$i].".disabled = false;\n";
                        
                        }
                        $i++; // インクリメント
                        $j++;
                    }
                    
                }
                
  
$labels .="],";
$gr_data .="],";
$gr_data_plan .="],";








?>

<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<!-- <link rel="stylesheet" href="css/main.css" /> -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/sample.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
<style>div{padding:5px;font-size:16px;}</style>
<title>打上高さ管理</title>
</head>
<body>

<header style="position: fixed;width:100%;z-index: 9999;">
  <nav class="navbar navbar-default" style="background:linear-gradient(to bottom, #8EA9DB 70%, #D9EAFF 100%); border-color:#305496;font-size:15px;display:flex;justify-content:space-between;">
    <div class="container-fluid"><p style="font-size: 20px;color:#E2EFDA;font-weight: bold;">CFTコンクリート施工管理システム</p></div>
    <div>
      <a class="navbar-brand" href="index2.php">施工管理へ</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>
</header>



<p style="height:100px;"></p>

<p style="font-size:20px;">現場名：<?= $gen_name ?></p>

<div id="edit_area">

</div>




<div style="display: flex; justify-content:space-around;margin:5px;width:800px;">
   <h3>打上り高さ管理</h3>
   <div>
      <input type="button" onclick="location.href='./accept.php'" value="受入検査へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;width:120px;'>
      <input type="button" onclick="location.href='./index2.php?id=<?= $pill_num ?>'" value="施工管理画面へ" style='coler:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background:#8EA9DB; border-radius:10px;'>
  </div>
</div>
<div>
    <div style="display: flex; justify-content:space-around;margin:5px;width:600px;">
    <p style="font-size:16px; margin:3px;">杭番号：<?= $pill_num ?></p>
    <p style="font-size:16px; margin:3px;">杭符号：<?= $pill_sign ?></p>
    <div style="font-size:20px;margin-left:30px;" id="time_total">00:00.000</div>
    </div>
    <div style="display: flex; justify-content:space-around;margin:5px;width:600px;">
    <p style="font-size:16px; margin:3px;">位置：<?=  $stcore_numX ?> － <?=  $stcore_numY ?></p>
    <p style="font-size:16px; margin:3px;">柱長：<?= $virtilength ?></p>
    <p style="font-size:16px; margin:3px;">打設予定数量：<?= $total ?>m3</p>
    </div>

  </div>

<div>
        <div style="display: flex; justify-content:flex-start;margin:5px;width:800px;">
        <form id="form0F" name="form1" action="time_act.php" method="post" style="font-size:14px;">
        <div style="display: flex; justify-content:flex-start;margin:5px;">
        <p style="font-size:20px;margin:10px;width:150px;">打設開始：</p>
        <input type="hidden" name="gen_name" value="<?= $gen_name ?>"/>
        <input type="hidden" name="pill_num" value="<?= $pill_num ?>"/>
        <input type="hidden" name="floor_num" value="打設開始"/>
        <input type="hidden" name="measure_height" value="00"/>
        <input style="margin:10px;" id="startButton0F" type="submit" onclick="disabled = true" value="打設　開始" />
        </div>
        </form>


        <input style="margin:10px;" id="resetButton" type="button" onclick="disabled = true" value="リセット" />




        
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
  </div>

<div style="display: flex; justify-content:flex-start;margin:0px;width:1000px;">
        <div>
            <?= $button ?>
        </div>
 
        <!-- 折れ線グラフ -->
        <div style="width:600px;height:500px;" >
            <canvas id="chart"></canvas>
          </div>

</div>

<!-- グラフ表示位置変更 -->
<script>
      var canvas;
      var ctx;

      function init() {
          canvas = document.getElementById("chart");
          // canvas.style.position = "absolute";
          // canvas.style.right = "10px";
          // canvas.style.top = "260px";
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


   // aaaa

        var ctx = document.getElementById("chart");
        var myLineChart = new Chart(ctx, {
          // グラフの種類：折れ線グラフを指定
          type: 'line',
          data: {
            // x軸の各メモリ
            <?= $labels ?>
          
            
            datasets: [
              {
                label: '打上り完了時間',
                <?= $gr_data ?>
                borderColor: "#ea2260",
                lineTension: 0, //<===追加
                fill: true, 
                backgroundColor:'rgb(255, 0, 0,0.3)'
                          
              },
                {
                label: '打上り計画時間', 
                <?= $gr_data_plan ?>
                borderColor: "#0000FF",
                lineTension: 0, //<===追加
                fill: true, 
                backgroundColor:'rgb(0, 0, 255,0.3)'
                
              },


            ],
          },
          options: {
            title: {
              display: true,
              text: '打上り高さ管理'
            },
           
            plugins: {
              datalabels: { 
                color: 'rgba(60,160,60,1)',
                anchor: 'start', 
                align: 'start',
                padding: {
                  top: 10
                }
              }
            },
            legend: {
                  display: true,  // 凡例を表示する
                  position: 'top',  // 凡例の位置を調整
                  labels: {
                    fontColor: '#333',  // 凡例テキストの色を設定
                    boxWidth: 20,  // 凡例アイコンの幅
                  }
                },

            scales: {
              y: [{
                        // type: 'time',
                        // distribution: 'series'
                        ticks: {// v3.5では 'yAxes' ではなく 'y' を使用します
                              suggestedMax: 60,
                              suggestedMin: 0,
                              stepSize: 5,  // 縦メモリのステップ数
                              callback: function(value) {
                                  return value + 'min';  // 各メモリのステップごとの表記
                              }
                          }
              }]
            },
          }
        });
        </script>

<!-- // トグルスイッチのJQuery -->

<script>

  $(".toggle").on("click", function() {
  $(".toggle").toggleClass("checked");
  if(!$('input[name="check"]').prop("checked")) {
    $(".toggle input").prop("checked", true);
  } else {
    $(".toggle input").prop("checked", false);
  }
});


// ストップウオッチのJS
// const startButton = document.getElementById('startButton0F');
const resetButton = document.getElementById('resetButton');
const form = document.getElementById("form");

// 各階の変数定義
const time_total = document.getElementById('time_total');
<?= $variable ?>


// const submitButton = document.getElementById("start");

// 開始時間
let startTime =0;
<?= $start_time ?>;


// 停止時間
let stopTime = 0;
<?= $stop_time ?>;
<?= $stop_time2 ?>;


// タイムアウトID
let timeoutID;
<?= $timeoutID ?>;

// 時間を表示する関数
function displayTime_total() {
    const currentTime_total = new Date(Date.now() - startTime1F + stopTime10F);
    const h = String(currentTime_total.getUTCHours()).padStart(2, '0');
    const m = String(currentTime_total.getUTCMinutes()).padStart(2, '0');
    const s = String(currentTime_total.getUTCSeconds()).padStart(2, '0');
    const ms = String(currentTime_total.getUTCMilliseconds()).padStart(3, '0');

    if (time_total) {
        time_total.textContent = `経過時間：${h}:${m}:${s}`;
    }

    timeoutID = setTimeout(displayTime_total, 10);
}

// ボタンのクリックイベント
// startButton1F.addEventListener('click', () => {
//   if(startTime1F = 0){
//     startTime1F = Date.now();
//   }
//   displayTime_total();
// })
stopButton10F.addEventListener('click', function() {
  clearTimeout(timeoutID);
  stopTime10F += (Date.now() - startTime);
  document.getElementById('edit_area').innerHTML = stopTime10F;
 })



<?= $time22 ?>

// スタートボタンがクリックされたら時間を進める


<?= $button_def ?>





// // ストップボタンがクリックされたら時間を止める
// stopButton.addEventListener('click', function() {
//   stopButton2.disabled = false;
//   stopButton.disabled = true;
//   resetButton.disabled = false;
//   clearTimeout(timeoutID);
//   stopTime += (Date.now() - startTime);
//   document.getElementById('edit_area').innerHTML = stopTime ;
// })





// リセットボタンがクリックされたら時間を0に戻す
// const Num = <?=$floorNum?>;
// if(Num = 0;){
//   resetButton.disabled = true;
// }else{
  resetButton.addEventListener('click', function() {
      startButton<?=$floorNum?>F.disabled = false;
      stopButton<?=$floorNum?>F.disabled = false;
      clearTimeout(timeoutID<?=$floorNum?>F);
      time<?=$floorNum?>F.textContent = '00:00.000';
      stopTime = 0;
      const currentTime = new Date(Date.now() - startTime + stopTime);
      const h = String(currentTime.getHours()-1).padStart(2, '0');
      const m = String(currentTime.getMinutes()).padStart(2, '0');
      const s = String(currentTime.getSeconds()).padStart(2, '0');
      const ms = String(currentTime.getMilliseconds()).padStart(3, '0');
      time.textContent = `経過時間：${m}:${s}`;
      timeoutID = setTimeout(displayTime, 10);
      document.getElementById('edit_area').innerHTML = stopTime ;
  })
// }

// ストップウオッチのJSここまで

 // フォーム送信イベント


//  startButton.onclick = function(event) {
//     event.preventDefault(); // デフォルトのフォーム送信を防ぐ

//     const form = document.getElementById("form");
//     const formData = new FormData(form);
//     const action = form.getAttribute("action");

//     fetch(action, {
//         method: 'POST',
//         body: formData,
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error('Network response was not ok');
//         }
//         return response.text();
//     })
//     .then(data => {
//         // タイマー開始
//         startButton.disabled = true;
//         // stopButton.disabled = false;
//         // resetButton.disabled = true;
//         startTime = Date.now();
//         displayTime();
//     })
//     .catch(error => {
//         console.error('エラー:', error);
//     })
// }


<?= $form22 ?>
<?= $form23 ?>

<?= $startButton_on ?>


</script>



</body>
</html>