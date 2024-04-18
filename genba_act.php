<?php
// //最初にSESSIONを開始！！ココ大事！！
// session_start();
// //POST値
// $lid = $_POST['lid'];
// $lpw = $_POST['lpw'];

// //1.  DB接続します
// require_once('funcs.php');
// $pdo = db_conn();

// //2. データ登録SQL作成
// // $stmt = $pdo->prepare("SELECT * FROM gs_user_table WHERE lid = :lid AND lpw=:lpw");
// $stmt = $pdo->prepare("select * FROM gs_user_table WHERE lid = :lid");
// $stmt->bindValue(':lid', $lid, PDO::PARAM_STR);
// // $stmt->bindValue(':lpw', $lpw, PDO::PARAM_STR); //* Hash化する場合はコメントアウトする
// $status = $stmt->execute();

// //3. SQL実行時にエラーがある場合STOP
// if($status==false){
//     sql_error($stmt);
// }

// //4. 抽出データ数を取得
// $val = $stmt->fetch();         //1レコードだけ取得する方法
// //$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()

// //5. 該当レコードがあればSESSIONに値を代入
// //* if(password_verify($lpw, $val["lpw"])){
//   // if( $val['id'] != "" ){
//     if( password_verify($lpw, $val["lpw"]) ){  
//     //Login成功時
//     $_SESSION['chk_ssid']  = session_id();
//     $_SESSION['kanri_flg'] = $val['kanri_flg'];
//     $_SESSION['name']      = $val['name'];
//     redirect('select.php');
//   }else{
//     //Login失敗時(Logout経由)
//     redirect('login.php');
//   }

// exit();


// <?php
session_start();

$gen_name = $_POST["gen_name"];
$gen_code = $_POST["gen_code"];
$gen_address = $_POST["gen_address"];

//1. 接続します
require_once('funcs.php');
$pdo = db_conn();





// ３．SQL文を用意(データ登録：INSERT)
$stmt = $pdo->prepare(
  "INSERT INTO gen( id, gen_name, gen_address, gen_code)
  VALUES( NULL, :gen_name, :gen_address, :gen_code)"
);

// 4. バインド変数を用意
$stmt->bindValue(':gen_name', $gen_name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':gen_address', $gen_address, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':gen_code', $gen_code, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)



// 5. 実行
$status = $stmt->execute();

// 6．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("ErrorMassage:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header('Location: genba.php');//ヘッダーロケーション（リダイレクト）
}
//処理終了
exit();

?>


