<?php
session_start();
ob_start();
if (isset($_POST["logout"])) {
  session_destroy();
}
//初期化DB用
$id;
//レコードがあるか
$rowCount;
//汎用select
$selectSql = "select * from `question` where id = ? and question_id = ?";
$insertSql = "insert into `question` (id,question_id,answer) values(?,?,?)";
$updateSql = "update `question` set id = ?, question_id = ?, answer = ? where id = ? and question_id = ?";

//DB接続情報----------------------------------------local
$dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
$db_user = "testuser";
$db_pass = "n8f]-j3&evUEWs)";
//----------------------------------------local

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
  //リダイレクト//----------------------------------------local
  header("Location:");
  //----------------------------------------local
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>6.for,while,do-while,foreachのループ問題</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="ansPageStyles06.css" media="all" />
</head>

<body>

  <div class="top-bar">
    <form action="" method="POST">
      <input type="submit" value="ログアウト" id="logoutbtn" name="logout">

    </form>
    <!-- <button id="logoutbtn" name="logout" onclick="location.href='http:\/\/marrotech.php.xdomain.jp/site/login/gakusyulogin.php'">ログアウト</button> -->
  </div>
  <?php
  if (isset($_POST["logout"])) {

    //no キャッシュ
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    session_destroy();
    //--------------------------------------------------------------------------local
    header("Location: ../login/gakusyulogin.php");
     //--------------------------------------------------------------------------
  }
  ?>


  <div class="header">
    <div class="inner">
      <h1>６.for,while,do-while,foreachのループ問題</h1>
      参考：<a href="https://wepicks.net/phpsample-control-for/" target="_blank">for 文を作成したい</a><br>
      参考：<a href="https://wepicks.net/phpsample-control-while/" target="_blank">while 文を作成したい</a><br>
      参考：<a href="https://wepicks.net/phpsample-control-do_while/" target="_blank">do while 文を作成したい</a><br>
      参考：<a href="https://wepicks.net/phpsample-control-foreach/" target="_blank">foreach 文を作成したい</a><br>
    </div>
  </div>
  <div class="mainContent">

    <div class="row">
      <h2>【問題１】for文</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id601" value="601">
        <p>質問: for文の使い方で誤っているコードを選びなさい。</p>
        <input type="radio" name="answer601" value="A" id="optionA">
        <label for="optionA">for ($i = 1; ; $i++){if($i > 10){break;}echo $i;}</label><br />
        <input type="radio" name="answer601" value="B" id="optionB">
        <label for="optionB">for($i = 1, $j = 1; $i <= 10, $j <=3; $i++, $j++)echo 'i:' .$i.' j:'.$j;</label><br />
            <input type="radio" name="answer601" value="C" id="optionC">
            <label for="optionC">for($i = 1; $i <= 10; $i++)echo $i;</label><br />
                <input type="radio" name="answer601" value="D" id="optionD">
                <label for="optionD">for ($i = 1; ; $i++){if($i > 10){ }echo $i;}</label><br />
                <input type="submit" value="解答する" name="ans601" id="ans601">
      </form>
    </div>
    <?php
    if (isset($_POST["ans601"]) && isset($_POST["answer601"])) {
      $answerVal601 = $_POST["answer601"];
      $_SESSION["answer601"] = $_POST["answer601"];

      //問題id取得
      $question_id = $_POST["question_id601"];

      //以下DB処理

      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
      // echo "<h1>" . $rowCount . "</h1>";

      //正解フラグ

      $resultStr;
      //update処理
      if ($rowCount == 1) {
        if ($answerVal601 == "D") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          // echo "<h1>" . $result . "</h1>";
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
        }
        //insert処理
      } else {
        if ($answerVal601 == "D") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        }
      }
    } else {
      //処理しない
    }
    if (is_null($_SESSION["answer601"])) {
      $_SESSION["answer601"] = "E";
    }

    if ($_SESSION["answer601"] == "D") {
      echo "<h1 class='maru'>○</h1>";
    } else if ($_SESSION["answer601"] == "E") {
    } else {
      echo "<h1 class='batu'>×</h1>";
    }
    ?>

    <!--以下5問ほど（row）をコピペ-->
    <div class="row">
      <h2>【問題２】while文</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id602" value="602">
        <p>質問: while文の使い方で誤っているコードを選びなさい。</p>
        <input type="radio" name="answer602" value="A" id="optionA">
        <label for="optionA">$i = 1;while($i < 1){echo $i;$i++;}echo '一度も実行されない。' ;</label><br>
            <input type="radio" name="answer602" value="B" id="optionB">
            <label for="optionB">$i = 0;while($i < 0){echo $i++;}</label><br>
                <input type="radio" name="answer602" value="C" id="optionC">
                <label for="optionC">$i = 1;while($i <= 10){if($i===5)break;echo $i;$i++;}</label><br>
                    <input type="radio" name="answer602" value="D" id="optionD">
                    <label for="optionD">$i = 1;while($i <= 10){if($i===5){echo '5になった' ;$i++;continue;}echo $i;$i++;}</label><br>
                        <input type="submit" value="解答する" name="ans602" id="ans602">
      </form>
    </div>
    <?php
    if (isset($_POST["ans602"]) && isset($_POST["answer602"])) {
      $answerVal602 = $_POST["answer602"];
      $_SESSION["answer602"] = $_POST["answer602"];
      //問題id取得
      $question_id = $_POST["question_id602"];

      //以下DB処理

      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
      // echo "<h1>" . $rowCount . "</h1>";

      //正解フラグ
      $flag;
      //update処理
      if ($rowCount == 1) {
        if ($answerVal602 == "B") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          // echo "<h1>" . $result . "</h1>";
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
        }
        //insert処理
      } else {
        if ($answerVal602 == "B") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        }
      }
    } else {
      //処理しない
    }
    if (is_null($_SESSION["answer602"])) {
      $_SESSION["answer602"] = "E";
    }

    if ($_SESSION["answer602"] == "B") {
      echo "<h1 class='maru'>○</h1>";
    } else if ($_SESSION["answer602"] == "E") {
    } else {
      echo "<h1 class='batu'>×</h1>";
    }
    ?>



    <div class="row">
      <h2>【問題３】do-while文</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id603" value="603">
        <p>質問: do-while文の使い方で誤っているコードを選びなさい。</p>
        <input type="radio" name="answer603" value="A" id="optionA">
        <label for="optionA">$i = 1;do{$i++;}while($i < 1);echo $i;</label><br>
            <input type="radio" name="answer603" value="B" id="optionB">
            <label for="optionB">$i = 1;do{if($i === 5)break;echo $i;$i++;}while($i <= 10);</label><br>
                <input type="radio" name="answer603" value="C" id="optionC">
                <label for="optionC">$i = 1;do{if($i === 5){echo '5になった';}echo $i;}while($i <= 10);</label><br>
                    <input type="radio" name="answer603" value="D" id="optionD">
                    <label for="optionD">$i = 1;do{if($i === 5){echo '5になった';$i++;continue;}echo $i;$i++;}while($i <= 10);</label><br>
                        <input type="submit" value="解答する" name="ans603" id="ans603">
      </form>
    </div>
    <?php
    if (isset($_POST["ans603"]) && isset($_POST["answer603"])) {
      $answerVal603 = $_POST["answer603"];
      $_SESSION["answer603"] = $_POST["answer603"];
      //問題id取得
      $question_id = $_POST["question_id603"];

      //以下DB処理

      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
      // echo "<h1>" . $rowCount . "</h1>";

      //正解フラグ
      $flag;
      //update処理
      if ($rowCount == 1) {
        if ($answerVal603 == "C") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          // echo "<h1>" . $result . "</h1>";
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
        }
        //insert処理
      } else {
        if ($answerVal603 == "C") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        }
      }
    } else {
      //null時は処理しない
    }
    if (is_null($_SESSION["answer603"])) {
      $_SESSION["answer603"] = "E";
    }

    if ($_SESSION["answer603"] == "C") {
      echo "<h1 class='maru'>○</h1>";
    } else if ($_SESSION["answer603"] == "E") {
    } else {
      echo "<h1 class='batu'>×</h1>";
    }
    ?>

    <div class="row">
      <h2>【問題４】foreach文</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id604" value="604">
        <p>質問: foreach文の使い方で誤っているコードを選びなさい。</p>
        <input type="radio" name="answer604" value="A" id="optionA">
        <label for="optionA">$array = array(1, 2, 3, 4, 5);foreach ($array as $value){ echo $value;}</label><br>
        <input type="radio" name="answer604" value="B" id="optionB">
        <label for="optionB">$i = 0;$array = array(1, 2, 3, 4, 5);foreach ($array as $value) {echo "\$array[$i] => $value";$i++;}</label><br>
        <input type="radio" name="answer604" value="C" id="optionC">
        <label for="optionC">$array = array("name" => 'PHP',"url" => 'https://wepicks.net');foreach ($array as $key => $value) {echo 'キー:[ '.$key.' ]/値:[ '.$value.' ]';}</label><br>
        <input type="radio" name="answer604" value="D" id="optionD">
        <label for="optionD">$array = array[1, 2, 3, 4, 5]; foreach ($array as $value) { echo $value; }</label><br>
        <input type="submit" value="解答する" name="ans604" id="ans604">
      </form>
    </div>
    <?php
    if (isset($_POST["ans604"]) && isset($_POST["answer604"])) {
      $answerVal604 = $_POST["answer604"];
      $_SESSION["answer604"] = $_POST["answer604"];
      //問題id取得
      $question_id = $_POST["question_id604"];

      //以下DB処理

      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
      // echo "<h1>" . $rowCount . "</h1>";

      //正解フラグ
      $flag;
      //update処理
      if ($rowCount == 1) {
        if ($answerVal604 == "D") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          // echo "<h1>" . $result . "</h1>";
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
        }
        //insert処理
      } else {
        if ($answerVal604 == "D") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        }
      }
    } else {
      //null時は処理しない
    }

    if (is_null($_SESSION["answer604"])) {
      $_SESSION["answer604"] = "E";
    }

    if ($_SESSION["answer604"] == "D") {
      echo "<h1 class='maru'>○</h1>";
    } else if ($_SESSION["answer604"] == "E") {
    } else {
      echo "<h1 class='batu'>×</h1>";
    }
    ?>

    <div class="row">
      <h2>【問題５】while/do-while文</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id605" value="605">
        <p>質問: while/do-while文の説明について誤っている選択肢を選びなさい。</p>
        <input type="radio" name="answer605" value="A" id="optionA">
        <label for="optionA">while文 の 式 の評価は各ループ処理の開始時に行われる</label><br>
        <input type="radio" name="answer605" value="B" id="optionB">
        <label for="optionB">最初に while文 の 式 の評価が「FALSE(偽)」だと 文 は1回も実行されない</label><br>
        <input type="radio" name="answer605" value="C" id="optionC">
        <label for="optionC">do～while文では文が最低1回は実行される</label><br>
        <input type="radio" name="answer605" value="D" id="optionD">
        <label for="optionD">do～while文の 式 の評価は各反復処理の前に行われる</label><br>
        <input type="submit" value="解答する" name="ans605" id="ans605">
      </form>
    </div>

    <?php
    if (isset($_POST["ans605"]) && isset($_POST["answer605"])) {
      $answerVal605 = $_POST["answer605"];
      $_SESSION["answer605"] = $_POST["answer605"];
      //問題id取得
      $question_id = $_POST["question_id605"];

      //以下DB処理

      $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
      // echo "<h1>" . $rowCount . "</h1>";

      //正解フラグ
      $flag;
      //update処理
      if ($rowCount == 1) {
        if ($answerVal605 == "D") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
          // echo "<h1>" . $result . "</h1>";
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeUpdate($pdo, $updateSql, $id, $question_id, $flag);
        }
        //insert処理
      } else {
        if ($answerVal605 == "D") {
          echo "<h4>正解です</h4>";
          $flag = true;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        } else {
          echo "<h4>不正解です</h4>";
          $flag = false;
          $result = executeInsert($pdo, $insertSql, $id, $question_id, $flag);
        }
      }
    } else {
      //null時は処理しない
    }
    if (is_null($_SESSION["answer605"])) {
      $_SESSION["answer605"] = "E";
    }

    if ($_SESSION["answer605"] == "D") {
      echo "<h1 class='maru'>○</h1>";
    } else if ($_SESSION["answer605"] == "E") {
    } else {
      echo "<h1 class='batu'>×</h1>";
    }
    ?>


    <div class="footer">
      <div class="inner">


        <div class="button-container">
          <!-- -----------------------------------local------------------------------- -->
          <button class="formbtn" onclick="location.href='../question/question05HTMLohmori.php'">
            ＜＜前の問題へ</button>
          <button id="listbtn" class="formbtn" onclick="location.href='../main/mainHTML.php'">一覧に戻る</button>
          <!-- -----------------------------------local------------------------------- -->

        </div>

        <div class="preview">
          <?php
          $quesMaxCount = 5;
          echo "正当数:";

          $countSQL = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 601 and 605";
          $stmt = $pdo->prepare($countSQL);
          $stmt->bindParam(1, $id);

          $stmt->execute();

          $countAnsNum = $stmt->fetchColumn();

          echo $countAnsNum . "/" . $quesMaxCount;
          ?>
        </div>

      </div>
    </div>
    <?php
    //DB閉じる
    $pdo = null;
    ?>

    <!-- JavaScriptの追加 -->
    <script src="kunii2JS.js">
    </script>
</body>

</html>