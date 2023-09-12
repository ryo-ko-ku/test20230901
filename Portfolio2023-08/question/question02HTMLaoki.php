<?php
  session_start();
  ob_start();
  //DB接続情報--------------------------------------------------------------------------
  $dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
$db_user = "testuser";
$db_pass = "n8f]-j3&evUEWs)";
  //-------------------------------------------------------------------------------

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
    //ログインしてなかったらログインページへ--------------------------------------------------------
    header("Location: ../login/gakusyulogin.php");
    //----------------------------------------------------------------------------------------------
  }

  // $correct_question;//総合正解数
  // $correct_answers;//このページの正解数

  // $quesSql = "select count(*) as num from question where id = 3 and answer = true";//総合正解数
  // $ansSql = "select count(*) from question where id = 3 and (question_id in ('201','202','203','204','205'))
  //            and answer = true";//このページの正解数
  
  // try{
  //   $pdo = new PDO($dsn, $db_user, $db_pass);
  //   $stmt = $pdo -> query($quesSql);
  //   $correct_question = $stmt -> fetchColumn();

  //   $stmt = $pdo -> query($ansSql);
  //   $correct_answers = $stmt -> fetchColumn();
  //   $pdo = null;

  // }catch(PDOException $e){
  //   exit("データベース接続失敗". $e -> getMessage());
  // }

  //関数をつかいたい！
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
  <title>２.配列型を使った処理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="ansPageStyles02.css" media="all" />
</head>

<body>
  <div class="header">
    <div class="inner">
      <h1>２.配列型を使った処理</h1>
      参考：<a href="https://wepicks.net/phpref-array/" target="_blank">PHPの配列をマスターする</a>
    </div>
  </div>
  <div class="mainContent">

    <div class="row">
      <h2>【問題１】<br/>$foods = array('soba' => 'そば', 'ramen' => 'ラーメン'); で「ラーメン」を表示させるコードを選びなさい</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id201" value="201">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer201" value="0" id="optionA" require>
        <label for="optionA">echo $foods[0];</label><br>
        <input type="radio" name="answer201" value="1" id="optionB">
        <label for="optionB">echo $foods['ramen'];</label><br>
        <input type="radio" name="answer201" value="0" id="optionC">
        <label for="optionC">echo $foods[1];</label><br>
        <input type="radio" name="answer201" value="0" id="optionD">
        <label for="optionD">echo $foods['ラーメン'];</label><br>
        <input type="submit" class="kaitou" id="idou201" value="解答する">
      </form>
    </div>

    <?php
    //DBに解答を送る
      //初期化
      $qId201;
      $ans201;
      $count = 0;

      if(isset($_POST["answer201"])){//問題201の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId201 = $_POST["question_id201"];//問題id
        $ans201 = $_POST["answer201"];//選択したvalue

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId201";            
          $stmt = $pdo -> query($sql);
          // $selSql = "select * from `question` where id = ':myId' AND question_id = ':qId'";
          // $stmt = $pdo->prepare($selSql);
          // $stmt->execute(array(
          //   ':myId' => $myId,
          //   ':qId' => $qId
          // ));
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans201 where id = $myId and question_id = $qId201";            
            $stmt = $pdo -> query($sql);
            //$upSql = "update question set answer = :ans201 where id = :myId and question_id = :qId";
            // $stmt = $pdo->prepare($upSql);
            // $stmt->execute(array(
            //   ':ans201' => $ans201,
            //   ':myId' => $myId,
            //   ':qId' => $qId
            // ));

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId201, $ans201)";
            $stmt = $pdo -> query($sql);
            // $insSql ="insert into question values (':myId', ':qId', ':ans201')";
            // $stmt = $pdo->prepare($insSql);
            // $stmt->execute(array(
            //   ':myId' => $myId,
            //   ':qId' => $qId,
            //   ':ans201' => $ans201
            // ));
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }
          //問題1：正誤判定
          //$qId = $_POST["question_id201"];//各問題に設定された問題ID
          //$answerVal201 = $_POST["answer201"];//選択した解答のvalue
          $_SESSION["ans201"] = $ans201;
      }
    ?>

      <!-- 問題1正誤表示 -->
      <?php
        // if(is_null($_SESSION["ans201"])){
        //   $_SESSION["ans201"] = 3;
        // }

        // if($_SESSION["ans201"] == 1){
        //   echo "<div class='answerMsg1'>◎</div>";
        // }else if ($_SESSION["ans201"] == 0){
        //   echo "<div class='answerMsg2'>×</div>";
        // }else{
        //   echo "";
        // }

        answerToDisp($_SESSION["ans201"]);
      ?>
    
    <!--以下5問ほど（row）をコピペ-->
    <div class="row">
      <h2>【問題２】<br/>配列の記述方法で正しいコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id202" value="202">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer202" value="0" id="optionA" require>
        <label for="optionA">$number = array(1=>'one,two,three,four,five');</label><br>
        <input type="radio" name="answer202" value="1" id="optionB">
        <label for="optionB">$number = array(1 =>'one','two','three','four','five');</label><br>
        <input type="radio" name="answer202" value="0" id="optionC">
        <label for="optionC">$number (1 =>'one','two','three','four','five');</label><br>
        <input type="radio" name="answer202" value="0" id="optionD">
        <label for="optionD">$number = (1 =>'one','two','three','four','five');</label><br>
        <input type="submit" class="kaitou" id="idou202" value="解答する">
      </form>
    </div>

    <?php
      $qId202;
      $ans202;
      $count = 0;

      if(isset($_POST["answer202"])){//問題202の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId202 = $_POST["question_id202"];
        $ans202 = $_POST["answer202"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId202";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans202 where id = $myId and question_id = $qId202";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId202, $ans202)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }

      //問題2：正誤判定
      //$qId = $_POST["question_id202"];//各問題に設定された問題ID
      $_SESSION["ans202"] = $ans202;//選択した解答のvalue
      // if($answerVal202 == 1){
      //   echo "正解です";
      // }
      }

    ?>
      <!-- 問題2正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans202"]);
      ?>

    <div class="row">
      <h2>【問題３】<br/>配列の記述方法で正しいコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id203" value="203">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer203" value="1" id="optionA" require>
        <label for="optionA">$aArray = array('hello',10,true);</label><br>
        <input type="radio" name="answer203" value="0" id="optionB">
        <label for="optionB">$aArray = 'hello';</label><br>
        <input type="radio" name="answer203" value="0" id="optionC">
        <label for="optionC">$aArray = 'hello',10,true;</label><br>
        <input type="radio" name="answer203" value="0" id="optionD">
        <label for="optionD">$aArray = array['hello',10,true];</label><br>
        <input type="submit" class="kaitou" id="idou203" value="解答する">
      </form>
    </div>

    <?php
      $qId203;
      $ans203;
      $count = 0;

      if(isset($_POST["answer203"])){//問題203の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId203 = $_POST["question_id203"];
        $ans203 = $_POST["answer203"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId203";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans203 where id = $myId and question_id = $qId203";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId203, $ans203)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }
        //問題3：正誤判定
        //$qId = $_POST["question_id203"];//各問題に設定された問題ID
        $_SESSION["ans203"] = $ans203;//選択した解答のvalue

      }
    ?>

      <!-- 問題3正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans203"]);
      ?>

    <div class="row">
      <h2>【問題４】<br/>配列の記述方法で正しいコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id204" value="204">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer204" value="0" id="optionA" require>
        <label for="optionA">$mail 'suzuki'  = 'suzuki@mail.com';</label><br>
        <input type="radio" name="answer204" value="0" id="optionB">
        <label for="optionB">$mail('suzuki') = 'suzuki@mail.com';</label><br>
        <input type="radio" name="answer204" value="1" id="optionC">
        <label for="optionC">$mail['suzuki'] = 'suzuki@mail.com';</label><br>
        <input type="radio" name="answer204" value="0" id="optionD">
        <label for="optionD">$mail{'suzuki'} = 'suzuki@mail.com';</label><br>
        <input type="submit" class="kaitou" id="idou204" value="解答する">
      </form>
    </div>

    <?php
      $qId204;
      $ans204;
      $count = 0;

      if(isset($_POST["answer204"])){//問題204の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId204 = $_POST["question_id204"];
        $ans204 = $_POST["answer204"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId204";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans204 where id = $myId and question_id = $qId204";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId204, $ans204)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }
        //問題4：正誤判定
        //$qId = $_POST["question_id204"];//各問題に設定された問題ID
        $_SESSION["ans204"] = $ans204;//選択した解答のvalue       
      }
    ?>

      <!-- 問題4正誤表示 -->
      <?php
        answerToDisp($_SESSION["ans204"]);
      ?>

    <div class="row">
      <h2>【問題５】<br/>配列の記述方法で正しいコードを選びなさい</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id205" value="205">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer205" value="1" id="optionA" require>
        <label for="optionA">$name = array('yamada','suzuki','katou');</label><br>
        <input type="radio" name="answer205" value="0" id="optionB">
        <label for="optionB">$name = array(yamada,suzuki,katou);</label><br>
        <input type="radio" name="answer205" value="0" id="optionC">
        <label for="optionC">$name = array['yamada','suzuki','katou'];</label><br>
        <input type="radio" name="answer205" value="0" id="optionD">
        <label for="optionD">$name = array[yamada,suzuki,katou];</label><br>
        <input type="submit" class="kaitou" id="idou205" value="解答する">
      </form>
    </div>

    <?php
      $qId205;
      $ans205;
      $count = 0;

      if(isset($_POST["answer205"])){//問題205の解答がセットされたら
        //$myId = "3";//実際はセッションスコープに保存されたidを使う$_SESSION["id"]
        $qId205 = $_POST["question_id205"];
        $ans205 = $_POST["answer205"];

        try{
          //DB接続
          //$pdo = new PDO($dsn, $db_user, $db_pass);
          //1.レコードがすでにあるか検索
          $sql = "select * from `question` where id = $myId AND question_id = $qId205";            
          $stmt = $pdo -> query($sql);
          $count = $stmt -> rowCount();//テーブルから取得した件数を取得

          if($count == 1){
            //2.あればアップデートをかける
            $sql = "update question set answer = $ans205 where id = $myId and question_id = $qId205";            
            $stmt = $pdo -> query($sql);

          }else if($count == 0){
            //3.無ければインサートする
            $sql = "insert into question values ($myId, $qId205, $ans205)";
            $stmt = $pdo -> query($sql);
          }
        }catch(PDOException $e){
          exit("データベース接続失敗". $e -> getMessage());
        }

        //問題5：正誤判定
        //$qId = $_POST["question_id205"];//各問題に設定された問題ID
        $_SESSION["ans205"] = $ans205;//選択した解答のvalue
      }
    ?>

      <!-- 問題5正誤表示 -->
      <?php
          answerToDisp($_SESSION["ans205"]);
      ?>

    <div class="footer">
      <div class="inner">
       
        <div class="button-container">
          <button id="beforeButton">≪ 前の問題へ</button>
          <button id="mainButton">一覧に戻る</button>
          <button id="nextButton">次の問題へ ≫</button>
        </div>

        <div class="preview">
          <?php
            //正解数の表示
            $correct_question;//総合正解数
            $correct_answers;//このページの正解数

            $quesSql = "select count(*) as num from question where id = $myId and answer = true";//総合正解数
            $ansSql = "select count(*) from question where id = $myId and (question_id in ('201','202','203','204','205'))
                      and answer = true";//このページの正解数

            try{
              //$pdo = new PDO($dsn, $db_user, $db_pass);
              $stmt = $pdo -> query($quesSql);
              $correct_question = $stmt -> fetchColumn();

              $stmt = $pdo -> query($ansSql);
              $correct_answers = $stmt -> fetchColumn();
              $pdo = null;

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
            //---------------------------------------------------------------
            header("Location: ../login/gakusyulogin.php");
            //---------------------------------------------------------------
          }
        ?>

        </div>
      </div>
    </div>
    <!-- JavaScriptの追加 -->
    <script src="aokiJS2.js"></script>
    <?php
      $pdo = null;
    ?>
</body>

</html>