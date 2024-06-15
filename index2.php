
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
      <a class="navbar-brand" href="login.php">TOPページ</a>
      <p>LoginID:<?= $userid ?></p>
  </div>
    </div>
  </nav>
</header>



<body>

<!-- メニュー表示 -->
  <div class="menu" style='display:flex;align-items: flex-end;'>
   <button type="button">柱選択変更</button>
   <a href="https://d28000001pumpeaw.my.salesforce-sites.com/" target="_blank">
   <button type="button">配車状況</button>
  </a>
   <button type="button">受入検査</button>
   <button type="button">施工状況</button>
  </div>
</div>   


<div id="video">
 <iframe
   id="eizo" 
   src="http://10.58.224.6/live.asp?r=201610270.17104665163885002" 
   title="PC画面">
  </iframe>
</div>


<!-- 設定 -->
 <h1 class="set">設定</h1>
 <button type="button">設定</button>

<!-- 柱選択 -->
<h1 class="set">柱選択</h1>

  <p>柱選択</p>

<!-- <a href="./img/PMO港南2丁目1F伏図.png"></a> -->



 <!--配車管理 -->
 <h1 class="car">配車管理</h1>



<a href="https://d28000001pumpeaw.my.salesforce-sites.com/" target="_blank">
  <button type="button">配車管理</button>

</a>
 
  <!-- <iframe src="http://www.tcc-measure.jp/cnst-manage0/pile_logon.php" width="1280" height="800" title="PC画面"></iframe> -->











  <!-- ここから上にコードを書く -->
  <!-- この中に記述していく -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="js/main.js"></script>
</body>

</html>