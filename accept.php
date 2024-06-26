<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/main.css" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
<title>受入検査</title>
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
<!-- <p style="font-size:20px;">現場名：<?= $gen_name ?></p> -->
<h3>受入検査</h3>

<form name="form21" action="accepttest_act.php" method="post"></form>
<div style="margin-left:20px;">
<p>検査回数</p>

<div style="display: flex; justify-content:space-around;margin:10px;">
              <div>
                  <div style="display: flex; justify-content:flex-start;margin:10px;width:500px;">
                  <p style="margin:5px;width:130px">スランプフロー</p>
                  <input type="text" name="memo" style="width:80px" />
                  <p style="margin:5px;">(cm)</p>
                  </div>

                  <div style="display: flex; justify-content:flex-start;margin:10px;width:500px;">
                  <p style="margin:5px;width:130px">空気量</p>
                  <input type="text" name="memo" style="width:80px"/>
                  <p style="margin:5px;">(％)</p>
                  </div>

                  <div style="display: flex; justify-content:flex-start;margin:10px;width:500px;">
                  <p style="margin:5px;width:130px">コンクリート温度</p>
                  <input type="text" name="memo" style="width:80px"/>
                  <p style="margin:5px;">(℃)</p>
                  </div>

                  <div style="display: flex; justify-content:flex-start;margin:10px;width:500px;">
                  <p style="margin:5px;width:130px">塩化物イオン量</p>
                  <input type="text" name="memo" style="width:80px"/>
                  <p style="margin:5px;">(kg/m3)</p>
                  </div>
              </div>

              <div>
                  <p>＊50cm到達時間・停止時間は参考値</p>
                  <div style="display: flex; justify-content:flex-start;margin:10px;width:500px;">
                  <p style="margin:5px;">50㎝到達時間(秒)</p>
                  </div>
                  
                  <div style="display: flex; justify-content:flex-start;margin:10px;width:500px;">
                  <p style="margin:5px;">停止時間(秒)</p>
                  </div>
                <!-- <form action="送信先パス" method="post" enctype="multipart/form-data"> -->
                  <dl style="margin-left:20px;">
                    <dd>
                      <div>
                      <label><span>カメラ（全景）</span>
                      <input type="file" capture="environment" accept="image/*"></label>
                      </div>
                      <div>
                        <label><span>カメラ（黒板）</span>
                        <input type="file" capture="environment" accept="image/*"></label>
                        </div>
                    </dd>
                  </dl>
              </div>
</div>


<div style="display: flex; justify-content:space-around;margin:10px;width:400px;">
<p>分離抵抗性</p>
<select name='bunri' style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background-color:#8EA9DB; border-radius:5px; width:200px;'>
  <option value='-'>－</option>
  <option value='良好'>良好</option>
  <option value='不良'>不良</option>
</select>
</div>

<div style="display: flex; justify-content:space-around;margin:10px;width:400px;">
<p>合否判定</p>
<select name='gouhi' style='color:white; border-color:#3b82f6;color:white; font-size:18px;margin:10px; background-color:#8EA9DB; border-radius:5px; width:200px;'>
  <option value='-'>－</option>
  <option value='合格'>合格</option>
  <option value='不合格'>不合格</option>
</select>
</div>

<div style="display: flex; justify-content:space-around;margin:10px;width:400px;">
<p>備考</p>
<input type="text" name="memo" /><br>
</div>

</div>




</form>

</body>
</html>

