<?php
  session_start(); //セッションを開始
  ob_start();

  //仮登録ログイン情報
  // Mailaddress：test123@poli.jp
  // Password：testpass

  //DB接続情報-----------------------------------------
  $dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
  $db_user = "testuser";
  $db_pass = "n8f]-j3&evUEWs)";
  //-----------------------------------------------
  //初期化
  $hash;
  $id;
  $errorMessage;
  $count;
  $_SESSION["loggedIn"] = false;//まだログインしていない状態

  if($_SERVER["REQUEST_METHOD"] === "POST"){//ログインフォームが押されたら
    try{
      if(isset($_POST["mail"]) && isset($_POST["password"])){
        
        $password = $_POST["password"];//入力されたパスワード
        $mail = $_POST["mail"];
    

        //1.メールアドレスを使って検索、パスワードを取ってくる
        $pdo = new PDO($dsn, $db_user, $db_pass);
        $stmt = $pdo -> prepare("select * from `user_login` where mail = :mail");
        // $stmt -> bindParam(1,$mail);
        //bindvalueでやると以下
        $stmt -> bindValue(":mail", $mail, PDO::PARAM_STR);

        $stmt -> execute();
        $count = $stmt -> rowCount();//何行取得できたか？
        // echo $count;
        //1件取得したなら(認証完了)
        if($count == 1){
          foreach($stmt as $user){
            $userPass = $user["password"];//userpass (DB)
            $id = $user["id"];
            $nickname = $user["nickname"];

          }

          // // //2.取ってこれたら取ってきた(ハッシュ化された)パスと入力された(認証したい)
          // if(password_verify($password, $hash)){ //---trueであればパスワード認証
          if($password == $userPass){
            //ここでログイン処理
            $_SESSION["id"] = $id;//取得されたidをセッションに保存
            $_SESSION["nickname"] = $nickname;//ニックネーム
            $_SESSION["loggedIn"] = true;//ログイン状態をセッションに保存
            //ログイン成功後mainページへ



            ///------------------------------------------------------------------
          

            header("Location: ../main/mainHTML.php");
            //----------------------------

            
            // echo "処理終了";
            exit();

          }else{ //---それ以外はパスワード認証失敗
            $errorMessage = "＊パスワードが間違っています＊";
          }

        }else{
          //3.取ってこれなかったら存在しないユーザー
          $errorMessage = "＊そのユーザーは登録されていません＊";
        }

      }
      $pdo = null;
    }catch(PDOException $e){
      exit("データベース接続失敗". $e -> getMessage());      
    }
  }
?>
   
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>Login Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="gakusyulogin.css" media="all" />
</head>

<body>
  <div class="header">
      <div class="inner">
        <h1>php Learning</h1>
      </div>
  </div>
  <br/>
  <br/>

  <!-- ユーザー未登録エラーメッセージ -->
  <div class="errMsg">
    <?php
    if(isset($errorMessage)){
      echo $errorMessage;
    }
    ?>
  </div>

  <!-- ログインフォーム -->
  <div class="mainContent">    
    <form method="POST">
    <p class="formbutton">
      <input type="text" maxlength="50" placeholder="Mail Address" pattern="^[!-~]+$" required name="mail"/><br/><br/>
      <input type="password" maxlength="15" placeholder="Password" pattern="^[a-zA-Z0-9]+$" required name="password"/><br/><br/>
      <input type='submit' class="btns" value='Login'/>
    </p>
    </form>
  </div>


  <!-- 新規ユーザー登録ページへ遷移 -->
  <div class="regiBtn-container">
    <button id="regiconBtn">新規登録</button>
  </div>

  <script>
    document.getElementById("regiconBtn").onclick = function() {

      //------------------------------------
      location.href = '../register/register.php';

      //-------------------------------

    };
  </script>



  <!-- ログイン情報(後で消す) -->
  <br/><br/><br/>
  テストログイン情報<br/>
  Mailaddress：test123@poli.jp<br/>
  Password：testpass<br/><br/>


</body>

</html>