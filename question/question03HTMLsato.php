<?php
session_start();
ob_start();

// if (isset($_POST["logout"])) {　ログアウトできない問題
//     session_destroy();
// }

//初期化DB用
$id;
//レコードがあるか
$rowCount;
//汎用select
$selectSql = "select * from `question` where id = ? and question_id = ?";
$insertSql = "insert into `question` (id,question_id,answer) values(?,?,?)";
$updateSql = "update `question` set id = ?, question_id = ?, answer = ? where id = ? and question_id = ?";

//DB接続情報-----------------------------------------------------------
$dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
$db_user = "testuser";
$db_pass = "n8f]-j3&evUEWs)";
//-----------------------------------------------------------------

//include file 
if ($_SESSION["loggedIn"] == true) {
    //セッションid
    $id = $_SESSION["id"];
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass);

        //汎用select function
        function executeSelect($pdo, $selectSql, $id, $question_id)
        {
            try {
                $stmt = $pdo->prepare($selectSql);
                $stmt->bindParam(1, $id);
                $stmt->bindParam(2, $question_id);

                $stmt->execute();

                $result = $stmt->rowCount();
                return $result;
            } catch (PDOException $e) {
                error_log('Error: ' . $e->getMessage());
                echo 'データベースエラーが発生しました。';
                return 0; // エラーが発生した場合はfalseを返す
            }
        }

        //汎用update function
        function executeUpdate($pdo, $updateSql, $id, $question_id, $flag)
        {
            try {
                $stmt = $pdo->prepare($updateSql);
                $stmt->bindParam(1, $id);
                $stmt->bindParam(2, $question_id);
                $stmt->bindParam(3, $flag);
                $stmt->bindParam(4, $id);
                $stmt->bindParam(5, $question_id);

                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                error_log('Error: ' . $e->getMessage());
                echo 'データベースエラーが発生しました。';
                return false; // エラーが発生した場合はfalseを返す
            }
        }

        //汎用insert function
        function executeInsert($pdo, $insertSql, $id, $question_id, $flag)
        {
            try {
                //insert into `question` (id,question_id,answer) values(?,?,?)
                $stmt = $pdo->prepare($insertSql);
                $stmt->bindParam(1, $id);
                $stmt->bindParam(2, $question_id);
                $stmt->bindParam(3, $flag);

                $stmt->execute();

                return true;
            } catch (PDOException $e) {
                error_log('Error: ' . $e->getMessage());
                echo 'データベースエラーが発生しました。';
                return false;
            }
        }
    } catch (PDOException $e) {
        echo "DB接続失敗:" . $e->getMessage();
    }
} else {
    //リダイレクト----------------------------------------------------------------local
    header("Location: ../login/gakusyulogin.php");
    //----------------------------------------------------------------------
}

      //以下、共通使用
      $flag;//正解フラグ

      $correct = "<font color = 'red'>　〇　正解　〇　</font>";
      $Incorrect = "<font color = 'blue'>　✕　不正解　✕　</font>";

      $_SESSION["correct"] = $correct;//正解
      $_SESSION["Incorrect"] = $Incorrect;//不正解

?>

<?php
    if (isset($_GET["logout"])) {
       //no キャッシュ
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        session_destroy();
        //--------------------------------------------------------------------------------
        header("Location: ../login/gakusyulogin.php");
        //------------------------------------------------------------------------------
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8" />
  <title>３.文字列操作に関する問題</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="ansPageStyles03.css" media="all" />
</head>

<body>
  <div class="header">
    <div class="inner">
      <h1>３.文字列操作に関する問題</div></h1>
      参考URLはこちら　⇒<a href="https://wepicks.net/phpsample-string-ketsugo/" target="_blank">　【　PHP で 文字列 や 変数 を結合したい　】</a><br/>
    </div>
  </div>
  <div class="mainContent">

    <div class="row">
      <h3>【問題１】$string = 'あいうえお';echo stristr($string, 'う');</h3>
      <form action="" method="POST">
        <input type="hidden" name="question_id301" value="301">
        <p>質問: 上記コードから得られる表示結果を選びなさい</p>
        <input type="radio" name="answer301" value="A">
        <label for="optionA">えお</label><br>
        <input type="radio" name="answer301" value="B">
        <label for="optionB">う</label><br>
        <input type="radio" name="answer301" value="C">
        <label for="optionC">うえお</label><br>
        <input type="radio" name="answer301" value="D">
        <label for="optionD">あいう</label><br>
        <input type="submit" value="解答する" name="ans301" id="ans301">
      </form>
    <div class="preview">
    <?php
    //回答したときは【どの回答を選んだか】と【〇✖】が表示され、
    //次の回答をすると、【〇✖】だけ残る仕様にする

    $a = "えお";
    $b = "う";
    $c = "うえお";//正解
    $d = "あいう";

    //当該問題の値と解答ボタンが押されたら正誤判定処理
    if (isset($_POST["ans301"]) && isset($_POST["answer301"])) {
      $answerVal301 = $_POST["answer301"];

      $_SESSION["answer301"] = $_POST["answer301"];

      //問題id取得
      $question_id = $_POST["question_id301"];

      //以下DB処理
      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);

      //update処理
      if ($rowCount == 1) {
          if ($answerVal301 == "A") { 
              echo "【　".$a."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          } else if($answerVal301 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal301 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal301 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }
          //insert処理
      } else {
          if ($answerVal301 == "A") {
              echo "【　".$a."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          } else if($answerVal301 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal301 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = true;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal301 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }
      }
      } else {
          //処理しない
      }

      //判定
      if (is_null($_SESSION["answer301"])) {
          $_SESSION["answer301"] = "XXX";
      }

      if ($_SESSION["answer301"] == "C") {
          echo $_SESSION["correct"]; 
      } else {
          echo $_SESSION["Incorrect"];
      }

      ?>
    </div>
  
    <div class="row">
      <h3>【問題２】文字列の変数$str1と$str2を$str3に連結して表示する場合</h3>
      <form action="" method="POST">
         <input type="hidden" name="question_id302" value="302">
        <p>質問: 以下の中から正しいコードを選びなさい</p>
        <input type="radio" name="answer302" value="A">
        <label for="optionA">echo $str3 = $str1.$str2;</label><br>
        <input type="radio" name="answer302" value="B">
        <label for="optionB">echo $str3 = $str1 + $str2;</label><br>
        <input type="radio" name="answer302" value="C">
        <label for="optionC">echo $str3 = $str1,$str2;</label><br>
        <input type="radio" name="answer302" value="D">
        <label for="optionD">echo $str3 = $str1:$str2;</label><br>
        <input type="submit" value="解答する" name="ans302" id="ans302">
      </form>
    </div>
    <div class="preview">
    <?php
    //回答したときは【どの回答を選んだか】と【〇✖】が表示され、
    //次の回答をすると、【〇✖】だけ残る仕様にする

    $a = "echo \$str3 = \$str1.\$str2;";//正解
    $b = "echo \$str3 = \$str1 + \$str2;";
    $c = "echo \$str3 = \$str1,\$str2;";
    $d = "echo \$str3 = \$str1:\$str2;";

    //当該問題の値と解答ボタンが押されたら正誤判定処理
    if (isset($_POST["ans302"]) && isset($_POST["answer302"])) {
      $answerVal302 = $_POST["answer302"];

      $_SESSION["answer302"] = $_POST["answer302"];

      //問題id取得
      $question_id = $_POST["question_id302"];

      //以下DB処理
      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);

      //update処理
      if ($rowCount == 1) {
          if ($answerVal302 == "A") { 
              echo "【　".$a."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          } else if($answerVal302 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal302 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal302 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }
          //insert処理
      } else {
          if ($answerVal302 == "A") {
              echo "【　".$a."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          } else if($answerVal302 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal302 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal302 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }
      }
      } else {
          //処理しない
      }

      //判定
      if (is_null($_SESSION["answer302"])) {
          $_SESSION["answer302"] = "XXX";
      }

      if ($_SESSION["answer302"] == "A") {//正解を入力
          echo $_SESSION["correct"]; 
      } else {
          echo $_SESSION["Incorrect"];
      }

      ?>
    </div>


    <div class="row">
      <h3>【問題３】文字列「あ」、「い」、「う」を連結して表示する場合</h3>
      <form action="" method="POST">
         <input type="hidden" name="question_id303" value="303">
        <p>質問: 以下の中から正しいコードを選びなさい</p>
        <input type="radio" name="answer303" value="A">
        <label for="optionA">echo 'あ','い','う';</label><br>
        <input type="radio" name="answer303" value="B">
        <label for="optionB">echo 'あ'.'い'.'う';</label><br>
        <input type="radio" name="answer303" value="C">
        <label for="optionC">echo 'あ' + 'い' + 'う';</label><br>
        <input type="radio" name="answer303" value="D">
        <label for="optionD">echo 'あ':'い':'う';</label><br>
        <input type="submit" value="解答する" name="ans303" id="ans303">
      </form>
    </div>
    <div class="preview">
    <?php
    //回答したときは【どの回答を選んだか】と【〇✖】が表示され、
    //次の回答をすると、【〇✖】だけ残る仕様にする

    $a = "echo 'あ','い','う';";
    $b = "echo 'あ'.'い'.'う';";//正解
    $c = "echo 'あ' + 'い' + 'う';";
    $d = "echo 'あ':'い':'う';";

    //当該問題の値と解答ボタンが押されたら正誤判定処理
    if (isset($_POST["ans303"]) && isset($_POST["answer303"])) {
      $answerVal303 = $_POST["answer303"];

      $_SESSION["answer303"] = $_POST["answer303"];

      //問題id取得
      $question_id = $_POST["question_id303"];

      //以下DB処理
      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);

      //update処理
      if ($rowCount == 1) {
          if ($answerVal303 == "A") { 
              echo "【　".$a."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          } else if($answerVal303 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal303 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal303 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }
          //insert処理
      } else {
          if ($answerVal303 == "A") {
              echo "【　".$a."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          } else if($answerVal303 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal303 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal303 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }
      }
      } else {
          //処理しない
      }

      //判定
      if (is_null($_SESSION["answer303"])) {
          $_SESSION["answer303"] = "XXX";
      }

      if ($_SESSION["answer303"] == "B") {//正解を入力
          echo $_SESSION["correct"]; 
      } else {
          echo $_SESSION["Incorrect"];
      }

      ?>
  </div>
    <div class="row">
      <h3>【問題４】$str= '２０１８年１０月５日は雨でした。';</h3>
      <form action="" method="POST">
         <input type="hidden" name="question_id304" value="304">
        <p>質問: 上記コードから「雨」を取り出す場合、正しいコードを選びなさい</p>
        <input type="radio" name="answer304" value="A">
        <label for="optionA">echo mb_substr($str, 11, 1, 'UTF-8');</label><br>
        <input type="radio" name="answer304" value="B">
        <label for="optionB">echo mb_substr($str, 12, 1, 'UTF-8');</label><br>
        <input type="radio" name="answer304" value="C">
        <label for="optionC">echo mb_substr($str, 33, 3, 'UTF-8');</label><br>
        <input type="radio" name="answer304" value="D">
        <label for="optionD">echo mb_substr($str, -15, 3, 'UTF-8');</label><br>
        <input type="submit" value="解答する" name="ans304" id="ans304">
      </form>
    </div>
    <div class="preview">
    <?php
    //回答したときは【どの回答を選んだか】と【〇✖】が表示され、
    //次の回答をすると、【〇✖】だけ残る仕様にする

    $a = "echo mb_substr(\$str, 11, 1, 'UTF-8');";//正解
    $b = "echo mb_substr(\$str, 12, 1, 'UTF-8');";
    $c = "echo mb_substr(\$str, 33, 3, 'UTF-8');";
    $d = "echo mb_substr(\$str, -15, 3, 'UTF-8');";

    //当該問題の値と解答ボタンが押されたら正誤判定処理
    if (isset($_POST["ans304"]) && isset($_POST["answer304"])) {
      $answerVal304 = $_POST["answer304"];

      $_SESSION["answer304"] = $_POST["answer304"];

      //問題id取得
      $question_id = $_POST["question_id304"];

      //以下DB処理
      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);

      //update処理
      if ($rowCount == 1) {
          if ($answerVal304 == "A") { 
              echo "【　".$a."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          } else if($answerVal304 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal304 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal304 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }
          //insert処理
      } else {
          if ($answerVal304 == "A") {
              echo "【　".$a."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          } else if($answerVal304 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal304 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal304 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }
      }
      } else {
          //処理しない
      }

      //判定
      if (is_null($_SESSION["answer304"])) {
          $_SESSION["answer304"] = "XXX";
      }

      if ($_SESSION["answer304"] == "A") {//正解を入力
          echo $_SESSION["correct"]; 
      } else {
          echo $_SESSION["Incorrect"];
      }

      ?>
    </div>

    <div class="row">
      <h3>【問題５】$str= '２０１８年５月１０日は晴でした。';</h3>
      <form action="" method="POST">
         <input type="hidden" name="question_id305" value="305">
        <p>質問: 上記コードから「晴」を取り出す場合、正しいコードを選びなさい</p>
        <input type="radio" name="answer305" value="A">
        <label for="optionA">echo mb_substr($str, -15, 3, 'UTF-8');</label><br>
        <input type="radio" name="answer305" value="B">
        <label for="optionB">echo mb_substr($str, -10, 2, 'UTF-8');</label><br>
        <input type="radio" name="answer305" value="C">
        <label for="optionC">echo mb_substr($str, -4, -1, 'UTF-8');</label><br>
        <input type="radio" name="answer305" value="D">
        <label for="optionD">echo mb_substr($str, -5, 1, 'UTF-8');</label><br>
        <input type="submit" value="解答する" name="ans305" id="ans305">
      </form>
    </div>
    <div class="preview">
    <?php
    //回答したときは【どの回答を選んだか】と【〇✖】が表示され、
    //次の回答をすると、【〇✖】だけ残る仕様にする

    $a = "echo mb_substr(\$str, -15, 3, 'UTF-8');";
    $b = "echo mb_substr(\$str, -10, 2, 'UTF-8');";
    $c = "echo mb_substr(\$str, -4, -1, 'UTF-8');";
    $d = "echo mb_substr(\$str, -5, 1, 'UTF-8');";//正解

    //当該問題の値と解答ボタンが押されたら正誤判定処理
    if (isset($_POST["ans305"]) && isset($_POST["answer305"])) {
      $answerVal305 = $_POST["answer305"];

      $_SESSION["answer305"] = $_POST["answer305"];

      //問題id取得
      $question_id = $_POST["question_id305"];

      //以下DB処理
      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);

      //update処理
      if ($rowCount == 1) {
          if ($answerVal305 == "A") { 
              echo "【　".$a."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          } else if($answerVal305 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal305 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }else if($answerVal305 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          }
          //insert処理
      } else {
          if ($answerVal305 == "A") {
              echo "【　".$a."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          } else if($answerVal305 == "B"){
              echo "【　".$b."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal305 == "C"){
              echo "【　".$c."　】を選びました"."<br/>";
              $flag = false;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }else if($answerVal305 == "D"){
              echo "【　".$d."　】を選びました"."<br/>";//正解
              $flag = true;
              $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
          }
      }
      } else {
          //処理しない
      }

      //判定
      if (is_null($_SESSION["answer305"])) {
          $_SESSION["answer305"] = "XXX";
      }

      if ($_SESSION["answer305"] == "D") {//正解を入力
          echo $_SESSION["correct"]; 
      } else {
          echo $_SESSION["Incorrect"];
      }

      ?>
    </div>

    <div class="footer">
      <div class="inner">
       
        <div class="button-container">
          <button onclick="location.href='../question/question02HTMLaoki.php'"><< 　前の問題へ</button>
          <button id="mainButton" onclick="location.href='../main/mainHTML.php'">メインページへ戻る</button>
          <button onclick="location.href='../question/question04HTMLkunii.php'">次の問題へ　 >></button>
        </div>

        <div class="preview">
                    <?php
                    $qCount = 5;//問題数　増えたら増やす（自動カウントではない）

                    //國井さんの
                    $countSQL = "select count(*) as now_answer_result from `question` where id = ? 
                                and answer = true and question_id between 301 and 305";
                    $stmt = $pdo->prepare($countSQL);

                    //PHPのbindParam()関数は、プリペアドステートメントで使用するSQL文の中で、
                    //プレースホルダーに値をバインドするための関数　（セレクト文に直接かけないのでバインドする）
                    $stmt->bindParam(1,$id);
                    $stmt->execute();
                    
                    $countAnsNum = $stmt->fetchColumn();

                    //echo "問題数【　".$qCount."　問　】中、"."<FONT COLOR=\"RED\">正答数は【　".$countAnsNum."　問　】</FONT>"."です";

                    //ほかの人と表示合わせる
                    echo "<h1>"."<font color=\"red\">　$countAnsNum</font>"."　/　$qCount"."</h1>"

                    ?>
        </div>

        <div class="button-container">
            <form action="" method="GET">
            <input id="logout" type="submit" value="ログアウト" id="logoutbtn" name="logout">

            <!-- ポストダメ<form action="http://marrotech.php.xdomain.jp/site/login/gakusyulogin.php" method="post">
                <button id="logout" type="submit" name="logout">ログアウト</button>
            </form> -->
            </form>
        </div>
      </div>
    </div>
    <?php
        //DB閉じる
        $pdo = null;
        ?>

    <!-- JavaScriptの追加 -->
    <script src="satoJS.js">
    </script>

</body>

</html>