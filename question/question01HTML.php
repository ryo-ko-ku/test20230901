<?php
  session_start();
  ob_start();
  //DB接続情報-------------------------------------------------------------------
  $dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
  $db_user = "testuser";
  $db_pass = "n8f]-j3&evUEWs)";
//--------------------------------------------------------------------------
  $myId;

  if ($_SESSION["loggedIn"]) {
    $myId = $_SESSION["id"];
    //DB接続
    try {
      $pdo = new PDO($dsn, $db_user, $db_pass);
    } catch (PDOException $e) {
      echo "DB接続失敗";
      echo $e->getMessage();
    }
  } else {
    //ログインしてなかったらログインページへ------------------------------------------------
    header("Location: ../login/gakusyulogin.php");
    //-----------------------------------------------------------------------------------
  }

  //正誤判定表示（まるばつ）
  function answerToDisp($val){
    if(is_null($val)){
      $val = 3;
    }    
    if($val == 1){
      echo "<div class='answerMsg1'>◎</div>";
    }else if($val == 0){
      echo "<div class='answerMsg2'>×</div>";
    }else{
      echo "";
    }
  }

?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>１.日付に関する問題</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="ansPageStyles01.css" media="all" />
</head>

<body>
  <div class="header">
    <div class="inner">
    <h1>１.日付に関する問題</h1>
      参考：<a href="https://wepicks.net/phpsample-date-2daytimedif/" target="_blank">PHP で 時間 を計算する</a><br>
      参考：<a href="https://wepicks.net/phpsample-date-format/" target="_blank">PHP 日付のフォーマット date()／strtotime()／DateTimeクラス</a><br>
    </div>
  </div>

  <div class="mainContent">

    <div class="row">
      <h2>【問題１】<br/>本日の日付を、YYYY-MM-DD HH:mm:SS の形式で表示させるコードを選びなさい</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id101" value="101">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer101" value="0" id="optionA" require>
        <label for="optionA">echo date('YYYY-MM-SS HH:mm:SS');</label><br>
        <input type="radio" name="answer101" value="1" id="optionB">
        <label for="optionB">echo date('Y-m-d H:i:s');</label><br>
        <input type="radio" name="answer101" value="0" id="optionC">
        <label for="optionC">echo date('Y-mm-d H:ii:u');</label><br>
        <input type="radio" name="answer101" value="0" id="optionD">
        <label for="optionD">echo date(new DateTime('today'));</label><br>
        <input type="submit" class="kaitou" id="idou101" value="解答する">
      </form>
    </div>

    <?php
    //DBに解答を送る
      //初期化
      $qId101;
      $ans101;
      $count = 0;

      if(isset($_POST["answer101"])){//問題101の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId101 = $_POST["question_id101"];
        $ans101 = $_POST["answer101"];
        // echo $_POST["question_id101"].":";
        // echo $_POST["answer101"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId101";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans101 where id = $myId and question_id = $qId101";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId101, $ans101)";
            $stmt = $pdo -> query($sql);
          }

        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }
          //問題1：正誤判定
          $_SESSION["ans101"] = $ans101;//選択した解答のvalue
      }
    ?>

      <!-- 問題1正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans101"]);
      ?>
    
    <!--以下5問ほど（row）をコピペ-->
    <div class="row">
      <h2>【問題２】<br/>本日から５日後の日付を、YYYY年MM月DD日(曜)の形式で表示させるコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id102" value="102">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer102" value="0" id="optionA" require>
        <label for="optionA">echo date ('Y年m月d日 (d)',strtotime ("five day"));</label><br>
        <input type="radio" name="answer102" value="1" id="optionB">
        <label for="optionB">echo date ('Y年m月d日 (D)',strtotime ("+5 day"));</label><br>
        <input type="radio" name="answer102" value="0" id="optionC">
        <label for="optionC">echo date ('Y年m月d日 (d)',strtotime ("+5 day"));</label><br>
        <input type="radio" name="answer102" value="0" id="optionD">
        <label for="optionD">echo date ('Y年m月d日 (D)',strtotime ("++4 day"));</label><br>
        <input type="submit" class="kaitou" id="idou102" value="解答する">
      </form>
    </div>

    <?php
      $qId102;
      $ans102;
      $count = 0;

      if(isset($_POST["answer102"])){//問題102の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId102 = $_POST["question_id102"];
        $ans102 = $_POST["answer102"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId102";         
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans102 where id = $myId and question_id = $qId102";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId102, $ans102)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }

      //問題2：正誤判定
      $_SESSION["ans102"] = $ans102;//選択した解答のvalue
      }

    ?>
      <!-- 問題2正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans102"]);
      ?>

    <div class="row">
      <h2>【問題３】<br/>先週の日付を、YYYY-MM-DD HH:mm:SS の形式で表示させるコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id103" value="103">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer103" value="0" id="optionA" require>
        <label for="optionA">echo date("Y-m-d H:ii:s", strtotime('last week'));</label><br>
        <input type="radio" name="answer103" value="0" id="optionB">
        <label for="optionB">echo date("Y-m-d H:i:s", strtotime('last year'));</label><br>
        <input type="radio" name="answer103" value="1" id="optionC">
        <label for="optionC">echo date("Y-m-d H:i:s", strtotime('last week'));</label><br>
        <input type="radio" name="answer103" value="0" id="optionD">
        <label for="optionD">echo date("Y-m-d H:i:u", strtotime('last week'));</label><br>
        <input type="submit" class="kaitou" id="idou103" value="解答する">
      </form>
    </div>

    <?php
      $qId103;
      $ans103;
      $count = 0;

      if(isset($_POST["answer103"])){//問題103の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId103 = $_POST["question_id103"];
        $ans103 = $_POST["answer103"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId103";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans103 where id = $myId and question_id = $qId103";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId103, $ans103)";
            $stmt = $pdo -> query($sql);
          }
          
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }
        //問題3：正誤判定
        $_SESSION["ans103"] = $ans103;//選択した解答のvalue

      }
    ?>

      <!-- 問題3正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans103"]);
      ?>

    <div class="row">
      <h2>【問題４】<br/>月末の日付を、YYYY-MM-DDの形式で表示させるコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id104" value="104">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer104" value="0" id="optionA" require>
        <label for="optionA">echo date('Y-M-D', strtotime("last day of this month"));</label><br>
        <input type="radio" name="answer104" value="0" id="optionB">
        <label for="optionB">echo date('yy-mm-dd', strtotime("last day of this month"));</label><br>
        <input type="radio" name="answer104" value="1" id="optionC">
        <label for="optionC">echo date('Y-m-d', strtotime("last day of this month"));</label><br>
        <input type="radio" name="answer104" value="0" id="optionD">
        <label for="optionD">echo date('yyyy-mm-dd', strtotime("last day of this month"));</label><br>
        <input type="submit" class="kaitou" id="idou104" value="解答する">
      </form>
    </div>

    <?php
      $qId104;
      $ans104;
      $count = 0;

      if(isset($_POST["answer104"])){//問題104の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId104 = $_POST["question_id104"];
        $ans104 = $_POST["answer104"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId104";         
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans104 where id = $myId and question_id = $qId104";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId104, $ans104)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }
        //問題4：正誤判定
        $_SESSION["ans104"] = $ans104;//選択した解答のvalue       
      }
    ?>

      <!-- 問題4正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans104"]);
      ?>

    <div class="row">
      <h2>【問題５】<br/>2019-1-1の日付を、YYYY-MM-DDの形式で表示させるコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id105" value="105">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer105" value="1" id="optionA" require>
        <label for="optionA">echo strtotime("1 January 2019");</label><br>
        <input type="radio" name="answer105" value="0" id="optionB">
        <label for="optionB">echo strtotime("1 1 2019");</label><br>
        <input type="radio" name="answer105" value="0" id="optionC">
        <label for="optionC">echo strtotime("January 1 2019");</label><br>
        <input type="radio" name="answer105" value="0" id="optionD">
        <label for="optionD">echo strtotime("2019 January 1");</label><br>
        <input type="submit" class="kaitou" id="idou105" value="解答する">
      </form>
    </div>

    <?php
      $qId105;
      $ans105;
      $count = 0;

      if(isset($_POST["answer105"])){//問題105の解答がセットされたら
        $qId105 = $_POST["question_id105"];
        $ans105 = $_POST["answer105"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId105";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans105 where id = $myId and question_id = $qId105";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId105, $ans105)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }

        //問題5：正誤判定
        $_SESSION["ans105"] = $ans105;//選択した解答のvalue
      }
    ?>

      <!-- 問題5正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans105"]);
      ?>

    <div class="footer">
      <div class="inner">
       
        <div class="button-container">
          <!-- <button id="beforeButton">≪ 前の問題へ</button> -->
          <button id="mainButton">一覧に戻る</button>
          <button id="nextButton">次の問題へ ≫</button>
        </div>

        <div class="preview">
          <?php
            //正解数の表示
            $correct_question;//総合正解数
            $correct_answers;//このページの正解数

            $quesSql = "select count(*) as num from question where id = $myId and answer = true";//総合正解数
            $ansSql = "select count(*) from question where id = $myId and (question_id in ('101','102','103','104','105'))
                      and answer = true";//このページの正解数

            try{
              //$pdo = new PDO($dsn, $db_user, $db_pass);
              $stmt = $pdo -> query($quesSql);
              $correct_question = $stmt -> fetchColumn();

              $stmt = $pdo -> query($ansSql);
              $correct_answers = $stmt -> fetchColumn();

            }catch(PDOException $e){
              exit("データベース接続失敗". $e -> getMessage());
            }

            echo "このページの正解数：".$correct_answers."／5<br/>";
            echo "総合正解数：$correct_question";
            
          ?>
        </div>
        
        <!-- ログアウトボタン -->
        <div class="logout-button">
          <form action="" method="GET">
          <input type="submit" name="out" value="ログアウト">
          </form>
        </div>
        <!-- ログアウト処理 -->
        <?php
        //actionを設定してtopページでやるべき？
          if(isset($_GET["out"])){
            session_destroy();
            //------------------------------------------------------------------------------------
            header("Location: ../login/gakusyulogin.php");
            //-----------------------------------------------------------------------------------
          }
        ?>

        </div>
      </div>
    </div>
    <!-- JavaScriptの追加 -->
    <script src="aokiJS1.js"></script>
    <?php
      $pdo = null;
    ?>

</body>
</html>