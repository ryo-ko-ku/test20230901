<?php
//DB接続情報
//---------------------------------------------------------------------------
$dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
$db_user = "testuser";
$db_pass = "n8f]-j3&evUEWs)";
//----------------------------------------------------------------------


$count;
$errMsg;
$completeMsg;

if (isset($_POST["mail"]) && isset($_POST["pass"]) && isset($_POST["name"])) { //入力フォーム全て入力されたなら
  $mail = $_POST["mail"];
  $pass = $_POST["pass"];
  $name = $_POST["name"];

  //1.データベースに存在するメールアドレスとかぶってないか
  try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    //レコードがあるか検索
    $stmt = $pdo->prepare("select id from `user_login` where mail = :mail");
    $stmt->bindValue(":mail", $_POST["mail"], PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount(); //テーブルから取得した件数を取得

    if ($count == 0) {
      //2.かぶってないなら本当にその内容で登録してもよいか確認


      //3.データベースに登録
      $sql = "insert into `user_login` (mail, password, nickname)
                      values(:mail, :pass, :name)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(":mail", $mail, PDO::PARAM_STR);
      $stmt->bindValue(":pass", $pass, PDO::PARAM_STR);
      $stmt->bindValue(":name", $name, PDO::PARAM_STR);
      $stmt->execute();

      //4.登録完了メッセージの表示(別ページでログインページへのボタンあった方がよい？)
      $completeMsg = "＊登録が完了しました＊";
    } else { //5.かぶってたらエラーメッセージ
      $errMsg = "＊既に登録されているユーザーです＊";
    }

    $pdo = null;
  } catch (PDOException $e) {
    exit("データベース接続失敗" . $e->getMessage());
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>New User Register Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="register.css" media="all" />
</head>

<body>
  <div class="header">
    <div class="inner">
      <h1>新規ユーザー登録</h1>
    </div>
  </div>

  <!-- 登録完了メッセージ -->
  <div class="comMsg">
    <?php
    if (isset($completeMsg)) {
      echo "<p>$completeMsg</p>";
    }
    ?>
  </div>

  <!-- エラーメッセージ -->
  <div class="errMsg">
    <?php
    //既に登録されてるアドレスだった時のエラー文
    if (isset($errMsg)) {
      echo "<p>$errMsg</p>";
    }
    ?>
  </div>


  <!-- ポップアップ  実装してもおもろいかもね------------------------------------ここ、パッともう少しだからもったいない-->
  <!-- 登錄ボタンと、確認ボタンのポップアップウィンドウがあるから、確認だけだったらいいんだけど　登録するをするとそもJSで取ってきて、サーバーに渡す、もしくはPOST通信で送るとか、サクッとは行かないからやめとこ -->
  <!-- <div class="popup_wrap">
    <input id="trigger" type="checkbox">
    <div class="popup_overlay">
      <label for="trigger" class="popup_trigger"></label>
      <div class="popup_content">
        <label for="trigger" class="close_btn">×</label>
        <p>こちらの内容で登録してよろしいですか？</p>
        <p>mailaddress</p>
        <p>name</p>
        <button>登　録</button>
      </div>
    </div>
  </div>
  <label for="trigger" class="open_btn">確　認</label>  -->
  <!-- ここまで -->


  <!-- 登録フォーム -->
  <div class="mainContent">
    <form action="" method="POST">
      Mail Address<br /><input type="text" maxlength="50" pattern="^[!-~]+$" required name="mail" /><br /><br />
      Password<br /><input type="text" maxlength="15" pattern="^[a-zA-Z0-9]+$" placeholder="15文字以内" required name="pass" /><br /><br />
      Name<br><input type="text" maxlength="15" placeholder="15文字以内" pattern=".*\S+.*" required name="name" /><br /><br />
      <div class="regiBtn">
        <input type='submit' class="btns" value='登録' id="registerBtn" />
      </div>
    </form>
  </div>

  <br />
  <div class="footer">
    <div class="inner">
      <!-- ログインページへ遷移 -->
      <div class="goLogin">
        <button id="gologBtn">ログインページへ</button>
        <script>
          document.getElementById("gologBtn").onclick = function() {
            //------------------------------------------------------------------
            location.href = '../login/gakusyulogin.php';
            //------------------------------------------------------------------
          };
        </script>
      </div>
    </div>
  </div>
  <!-- <script>
    document.addEventListener("DOMContentLoaded", function() {
      const trigger = document.getElementById("trigger");
      const openBtn = document.querySelector(".open_btn");
      const closeBtn = document.querySelector(".close_btn");
      const popupOverlay = document.querySelector(".popup_overlay");

      // ボタンがクリックされたときの処理
      openBtn.addEventListener("click", function() {
        popupOverlay.style.display = "block";
      });

      // 閉じるボタンがクリックされたときの処理
      closeBtn.addEventListener("click", function() {
        popupOverlay.style.display = "none";
      });

      // オーバーレイ部分がクリックされたときの処理
      popupOverlay.addEventListener("click", function(e) {
        if (e.target === popupOverlay) {
          popupOverlay.style.display = "none";
        }
      });
    });
  </script> -->
</body>

</html>