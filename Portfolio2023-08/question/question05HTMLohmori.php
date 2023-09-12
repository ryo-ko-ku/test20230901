<?php
// ********皆さんのコードを参考
  session_start();//セッションスタート
  ob_start();//まとめて出力したい時、同じ処理をまとめたい時
            //outputBuffering_start

  //DB接続情報----------------------------------------------------------
  $dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
  $db_user = "testuser";
  $db_pass = "n8f]-j3&evUEWs)";
 //DB接続情報----------------------------------------------------------local


  if ($_SESSION["loggedIn"]) {//ログインがtrueなら
    $mId = $_SESSION["id"];//セッションスコープのidを$idに保存
    //DB接続
    try {
      $pdo = new PDO($dsn, $db_user, $db_pass);

    } catch (PDOException $e) {
      echo "DB接続失敗";
      echo $e->getMessage();
    }
  } else {// ログインしてなかったらログインページへ
    //------------------------------------------------
    header("Location: ../login/gakusyulogin.php");
    //---------------------------------------------------------local

  }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>５.If, &&, xor, is_numeric()などの論理問題</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="ansPageStyles05.css" media="all" />
</head>

<body>

  <div class="header">
    <div class="inner">
      <h1>５.If, &&, xor, is_numeric()などの論理問題</h1>
      参考：<a href="https://wepicks.net/phpref-structures_if/" target="_blank">PHP の if 文 条件式による分岐処理</a><br>
      参考：<a href="https://wepicks.net/phpsample-operators-logical/" target="_blank">論理演算子を利用したい</a><br>
      参考：<a href="https://wepicks.net/phpsample-operators-comparison/" target="_blank">比較演算子を利用したい</a><br>
      参考：<a href="https://wepicks.net/phpfunction-var-isnumeric/" target="_blank">php is_numeric 変数が数値か文字列型の数字かどうかチェックする</a><br>
    <!-- <P>タグで囲えば整列出来るが、文字列の長さがバラバラなので、いい感じに整列できない・・・
      <p style="text-align:center">,text-align: start; <p>-->
    </div>
  </div>

  <div class="mainContent">

    <div class="row">
      <h2>【問題１】if文、比較演算子</h2>
      <form action="" method="POST">
        <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
        <input type="hidden" name="question_id501" value="501">
        <p>質問: 以下の選択肢から誤っているものを選んでください。</p>
        <input type="radio" name="answer501" value="A" id="optionA">
        <label for="optionA">if(null == "") echo '結果は TRUE です。';</label><br>
        <input type="radio" name="answer501" value="B" id="optionB">
        <label for="optionB">if(1 !== 2) echo '結果は TRUE です。'; else '結果は FALSE です。';</label><br>
        <input type="radio" name="answer501" value="C" id="optionC">
        <label for="optionC">if(false = "") echo '結果は TRUE です。';</label><br>
        <input type="radio" name="answer501" value="D" id="optionD">
        <label for="optionD">if(1 != 2) echo '結果は TRUE です。'; else '結果は FALSE です。';</label><br>
        <input type="submit" name="a501" value="解答する" id="a501">
      </form>
    </div>

    <?php
    // question_idがDBにあるかどうか

    // $mId;
    $qId501;//問題ID
    $answer501;//選んだ解答
    $a501;//解答ボタン
    $count;

    $answerVal = $_POST["answer501"];

    // $mId = 4;
    $qId501 = $_POST["question_id501"];
    $ans501 = false;//デフォルト不正解

    try{
      $pdo = new PDO($dsn,$db_user,$db_pass);

      $sql = "select count(*) as CNT from question where id = '$mId' and question_id = '$qId501'";
      $count = (int)$pdo->query($sql)->fetchColumn();
      //var_dump($count);
      //string型として表示されるので、キャストが必要⇒SQL文にas別名を付けると解決するかも？？（未検証）
      // $count = "select count(*) from question where id ='$mId' and question_id = '$qId501'";
      // $stmt = $pdo->query($count)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
      // var_dump($stmt);

      if($count == 0){//レコードなしの場合、作る
        // echo "反映されてる１";

        // $sql = "insert into question values ('$mId','$qId501','$ans501')";
        // $stmt = $pdo -> query($sql);
        // // $stmt = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
        // // var_dump($stmt); 
        // echo "処理完了";

        //０のレコード対策
        //下に書いていた文をここに持ってきた

        //解答が選択されて、解答ボタンが押されているとき
        if(isset($_POST["answer501"]) && isset($_POST["a501"])){

          if($answerVal == "C"){
            $ans501 = true;
            // $sql = "insert into question values ('".$myId."', '".$qId201."', '".$ans201."')";
            $sql = "insert into question values ('$mId','$qId501','$ans501')";
            // $sql = "update question set answer = '$ans501' where id = '$mId' and question_id = '$qId501'";
            $stmt = $pdo->query($sql);
              // echo "接続してる１";
  
          }elseif($answerVal != "C"){
            $ans501 = false;
            $sql = "insert into question values ('$mId','$qId501','$ans501')";
            $stmt = $pdo->query($sql);
              // echo "接続してる2";
          }

        }


      }elseif($count == 1){//レコードありの場合
        // echo "反映されてる２";

        if(isset($_POST["answer501"]) && isset($_POST["a501"])){

          if($answerVal == "C"){
            $ans501 = true;
            // $sql = "insert into `question` (id,question_id,answer) values ($mId,$qId501,$answer501)";
            $sql = "update question set answer = '$ans501' where id = '$mId' and question_id = '$qId501'";
            $stmt = $pdo->query($sql);
              // echo "接続してる3";
  
          }elseif($answerVal != "C"){
            $ans501 = false;
            // $sql = "insert into `question` (id,question_id,answer) values ($mId,$qId501,$answer501)";
            $sql = "update question set answer = '$ans501' where id = '$mId' and question_id = '$qId501'";
            $stmt = $pdo->query($sql);
              // echo "接続してる4";
          }

        }


      }

      // この程度の規模であれば、null処理は不要との事なので、コメントアウトしておく
      // 一番最後にnull処理を入れて置く
      // $pdo = null;
      

    }catch(PDOException $e){
      exit('データベース接続失敗'.$e->getMessage());
    }

    ?>

    <?php 
        // 確認用、コメントアウトしてね。
        $qId = $_POST["question_id501"];
        // $answerVal = $_POST["answer501"];
        // echo "DB登録-問題ID：".$qId."正解判定value".$answerVal."<br>\n";
        if(isset($answerVal)){
          // echo "<br>\n";
          if($answerVal == "C"){
            // echo "正解！！";
          }elseif($answerVal == "A" || $answerVal == "B" || $answerVal == "D"){
            // echo "不正解・・・";
          }else{
            //何も表示しない
          }
          $_SESSION["answer501"] = $_POST["answer501"];

        }

    ?>

    <?php


      if(is_null($_SESSION["answer501"])){
        $_SESSION["answer501"] = "E";
      }

      if($_SESSION["answer501"] == "C"){
        echo "<div class='maru'>◎</div>";
      }else if ($_SESSION["answer501"] == "E"){
        
      }else{
        echo "<div class='batu'>✖</div>";
      }
    ?>

    <?php
    // if (isset($_POST["ans502"]) && isset($_POST["answer502"])) {
    //   $answerVal502 = $_POST["answer502"];
    //   //以下DBに接続
    //   if ($answerVal502 == "A") {
    //       echo "正解です";
    //   } else {
    //       echo "不正解です";
    //   }
    // } else {
    //     //処理しない
    // }
    
    ?>


    <!--以下5問ほど（row）をコピペ-->
    <div class="row">
      <h2>【問題２】論理積 and,&&</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id502" value="502">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer502" value="A" id="optionA">
        <label for="optionA">$a = 1;$b = 2;if($a === 1 and $b === 2) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer502" value="B" id="optionB">
        <label for="optionB">$a = 1;$b = 2;if(($a === 1) & ($b === 2)) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer502" value="C" id="optionC">
        <label for="optionC">$a = 1;$b = 2;if(($a === 1) && ($b === 2)) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer502" value="D" id="optionD">
        <label for="optionD">$a = 1;$b = 2;if($a === 1and $b === 2) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="submit" name="a502" value="解答する" id="a502">
      </form>

    <?php 
    // question_idがDBにあるかどうか

    // $mId;
    $qId502;//問題ID
    $answer502;//選んだ解答
    $a502;//解答ボタン
    $count;

    $answerVal = $_POST["answer502"];

    // $mId = 4;
    $qId502 = $_POST["question_id502"];
    $ans502 = false;//デフォルト不正解

    try{
      $pdo = new PDO($dsn,$db_user,$db_pass);

      $sql = "select count(*) as CNT from question where id = '$mId' and question_id = '$qId502'";
      $count = (int)$pdo->query($sql)->fetchColumn();

      if($count == 0){//レコードなしの場合、作る
        // echo "反映されてる１";

        //解答が選択されて、解答ボタンが押されているとき
        if(isset($_POST["answer502"]) && isset($_POST["a502"])){

          if($answerVal == "C"){
            $ans502 = true;
           
            $sql = "insert into question values ('$mId','$qId502','$ans502')";
           
            $stmt = $pdo->query($sql);
              // echo "接続してる１";
  
          }elseif($answerVal != "C"){
            $ans502 = false;
            $sql = "insert into question values ('$mId','$qId502','$ans502')";
            $stmt = $pdo->query($sql);
              // echo "接続してる2";
          }

        }


      }elseif($count == 1){//レコードありの場合
        // echo "反映されてる２";

        if(isset($_POST["answer502"]) && isset($_POST["a502"])){

          if($answerVal == "C"){
            $ans502 = true;
            
            $sql = "update question set answer = '$ans502' where id = '$mId' and question_id = '$qId502'";
            $stmt = $pdo->query($sql);
              // echo "接続してる3";
  
          }elseif($answerVal != "C"){
            $ans502 = false;
          
            $sql = "update question set answer = '$ans502' where id = '$mId' and question_id = '$qId502'";
            $stmt = $pdo->query($sql);
              // echo "接続してる4";
          }

        }

      }

      // $pdo = null;

    }catch(PDOException $e){
      exit('データベース接続失敗'.$e->getMessage());
    }

    ?>

    <?php 
        // 確認用、コメントアウトしてね。
        $qId = $_POST["question_id502"];

        if(isset($answerVal)){
          echo "<br>\n";
          if($answerVal == "C"){
            // echo "正解！！";
          }elseif($answerVal == "A" || $answerVal == "B" || $answerVal == "D"){
            // echo "不正解・・・";
          }else{
            //何も表示しない
          }

          $_SESSION["answer502"] = $_POST["answer502"];
        }

    ?>

    <?php
      

      if(is_null($_SESSION["answer502"])){
        $_SESSION["answer502"] = "E";
      }

      if($_SESSION["answer502"] == "C"){
        echo "<div class='maru'>◎</div>";
      }else if ($_SESSION["answer502"] == "E"){
        
      }else{
        echo "<div class='batu'>✖</div>";
      }
    ?>

    </div>

    <div class="row">
      <h2>【問題３】排他的論理和 xor</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id503" value="503">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer503" value="A" id="optionA">
        <label for="optionA">if(($a + $b === 3) xor ($b - $a === -1)) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer503" value="B" id="optionB">
        <label for="optionB">if($a + $b === 3 xor $b - $a === -1) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer503" value="C" id="optionC">
        <label for="optionC">if(($a xor $b) xor ($b xor $a)) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer503" value="D" id="optionD">
        <label for="optionD">if($a + $b xor 3) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="submit" name="a503" value="解答する" id="a503">
      </form>
    </div>

    <?php 
    // question_idがDBにあるかどうか

    // $mId;
    $qId503;//問題ID
    $answer503;//選んだ解答
    $a503;//解答ボタン
    $count;

    $answerVal = $_POST["answer503"];

    // $mId = 4;
    $qId503 = $_POST["question_id503"];
    $ans503 = false;//デフォルト不正解

    try{
      $pdo = new PDO($dsn,$db_user,$db_pass);

      $sql = "select count(*) as CNT from question where id = '$mId' and question_id = '$qId503'";
      $count = (int)$pdo->query($sql)->fetchColumn();

      if($count == 0){//レコードなしの場合、作る
        // echo "反映されてる１";

        //解答が選択されて、解答ボタンが押されているとき
        if(isset($_POST["answer503"]) && isset($_POST["a503"])){

          if($answerVal == "A"){
            $ans503 = true;
           
            $sql = "insert into question values ('$mId','$qId503','$ans503')";
           
            $stmt = $pdo->query($sql);
              // echo "接続してる１";
  
          }elseif($answerVal != "A"){
            $ans503 = false;
            $sql = "insert into question values ('$mId','$qId503','$ans503')";
            $stmt = $pdo->query($sql);
              // echo "接続してる2";
          }

        }


      }elseif($count == 1){//レコードありの場合
        // echo "反映されてる２";

        if(isset($_POST["answer503"]) && isset($_POST["a503"])){

          if($answerVal == "A"){
            $ans503 = true;
            
            $sql = "update question set answer = '$ans503' where id = '$mId' and question_id = '$qId503'";
            $stmt = $pdo->query($sql);
              // echo "接続してる3";
  
          }elseif($answerVal != "A"){
            $ans503 = false;
          
            $sql = "update question set answer = '$ans503' where id = '$mId' and question_id = '$qId503'";
            $stmt = $pdo->query($sql);
              // echo "接続してる4";
          }

        }

      }

      // $pdo = null;

    }catch(PDOException $e){
      exit('データベース接続失敗'.$e->getMessage());
    }

    ?>

<?php 
        // 確認用、コメントアウトしてね。
        $qId = $_POST["question_id503"];

        if(isset($answerVal)){
          // echo "<br>\n";
          if($answerVal == "A"){
            // echo "正解！！";
          }elseif($answerVal == "B" || $answerVal == "C" || $answerVal == "D"){
            // echo "不正解・・・";
          }else{
            //何も表示しない
          }
          $_SESSION["answer503"] = $_POST["answer503"];

        }

    ?>

    <?php


      if(is_null($_SESSION["answer503"])){
        $_SESSION["answer503"] = "E";
      }

      if($_SESSION["answer503"] == "A"){
        echo "<div class='maru'>◎</div>";
      }else if ($_SESSION["answer503"] == "E"){
        
      }else{
        echo "<div class='batu'>✖</div>";
      }
    ?>


    <div class="row">
      <h2>【問題４】is_numeric()</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id504" value="504">
        <p>質問: 以下の選択肢から誤っているものを選んでください。</p>
        <input type="radio" name="answer504" value="A" id="optionA">
        <label for="optionA">$int = +1024;echo is_numeric($int) ? "数値または文字列型" : "数値ではない";</label><br>
        <input type="radio" name="answer504" value="B" id="optionB">
        <label for="optionB">if (is_numeric($value)) {echo $value." 数値または文字列型の数字です。"}</label><br>
        <input type="radio" name="answer504" value="C" id="optionC">
        <label for="optionC">$int = 'Hello,World';echo is_numeric($int) ? "数値または文字列型" : "数値ではない";</label><br>
        <input type="radio" name="answer504" value="D" id="optionD">
        <label for="optionD">if (is_numeric{123}) {echo " 数値または文字列型の数字です。"}</label><br>
        <input type="submit" name="a504"value="解答する" id="a504">
      </form>
    </div>

    <?php 
    // question_idがDBにあるかどうか

    // $mId;
    $qId504;//問題ID
    $answer504;//選んだ解答
    $a504;//解答ボタン
    $count;

    $answerVal = $_POST["answer504"];

    // $mId = 4;
    $qId504 = $_POST["question_id504"];
    $ans504 = false;//デフォルト不正解

    try{
      $pdo = new PDO($dsn,$db_user,$db_pass);

      $sql = "select count(*) as CNT from question where id = '$mId' and question_id = '$qId504'";
      $count = (int)$pdo->query($sql)->fetchColumn();

      if($count == 0){//レコードなしの場合、作る
        // echo "反映されてる１";

        //解答が選択されて、解答ボタンが押されているとき
        if(isset($_POST["answer504"]) && isset($_POST["a504"])){

          if($answerVal == "D"){
            $ans504 = true;
           
            $sql = "insert into question values ('$mId','$qId504','$ans504')";
           
            $stmt = $pdo->query($sql);
              // echo "接続してる１";
  
          }elseif($answerVal != "D"){
            $ans504 = false;
            $sql = "insert into question values ('$mId','$qId504','$ans504')";
            $stmt = $pdo->query($sql);
              // echo "接続してる2";
          }

        }


      }elseif($count == 1){//レコードありの場合
        // echo "反映されてる２";

        if(isset($_POST["answer504"]) && isset($_POST["a504"])){

          if($answerVal == "D"){
            $ans504 = true;
            
            $sql = "update question set answer = '$ans504' where id = '$mId' and question_id = '$qId504'";
            $stmt = $pdo->query($sql);
              // echo "接続してる3";
  
          }elseif($answerVal != "D"){
            $ans504 = false;
          
            $sql = "update question set answer = '$ans504' where id = '$mId' and question_id = '$qId504'";
            $stmt = $pdo->query($sql);
              // echo "接続してる4";
          }

        }

      }

      // $pdo = null;

    }catch(PDOException $e){
      exit('データベース接続失敗'.$e->getMessage());
    }

    ?>

<?php 
        // 確認用、コメントアウトしてね。
        $qId = $_POST["question_id504"];

        if(isset($answerVal)){
          // echo "<br>\n";
          if($answerVal == "D"){
            // echo "正解！！";
          }elseif($answerVal == "A" || $answerVal == "B" || $answerVal == "C"){
            // echo "不正解・・・";
          }else{
            //何も表示しない
          }
          $_SESSION["answer504"] = $_POST["answer504"];
        }

    ?>

    <?php


      if(is_null($_SESSION["answer504"])){
        $_SESSION["answer504"] = "E";
      }

      if($_SESSION["answer504"] == "D"){
        echo "<div class='maru'>◎</div>";
      }else if ($_SESSION["answer504"] == "E"){
        
      }else{
        echo "<div class='batu'>✖</div>";
      }
    ?>


    <div class="row">
      <h2>【問題５】比較演算子</h2>
      <form action="" method="POST">
         <!--hidden のvalueに問題idを書く 問題IDは（ジャンル、分類、大問）1の01とか（問題数）＝101-->
         <input type="hidden" name="question_id505" value="505">
        <p>質問: 以下の選択肢から正しいものを選んでください。</p>
        <input type="radio" name="answer505" value="A" id="optionA">
        <label for="optionA">if($a + $b === !-3) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer505" value="B" id="optionB">
        <label for="optionB">if(!($a + $b === -3)) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer505" value="C" id="optionC">
        <label for="optionC">!if($a + $b === -3) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="radio" name="answer505" value="D" id="optionD">
        <label for="optionD">if(!$a + $b === -3) echo '結果はTRUEです。'; else echo '結果はFALSEです。';</label><br>
        <input type="submit" name="a505" value="解答する" id="a505">
      </form>
    </div>

    <?php 
    // question_idがDBにあるかどうか

    // $mId;
    $qId505;//問題ID
    $answer505;//選んだ解答
    $a505;//解答ボタン
    $count;

    $answerVal = $_POST["answer505"];

    // $mId = 4;
    $qId505 = $_POST["question_id505"];
    $ans505 = false;//デフォルト不正解

    try{
      $pdo = new PDO($dsn,$db_user,$db_pass);

      $sql = "select count(*) as CNT from question where id = '$mId' and question_id = '$qId505'";
      $count = (int)$pdo->query($sql)->fetchColumn();

      if($count == 0){//レコードなしの場合、作る
        // echo "反映されてる１";

        //解答が選択されて、解答ボタンが押されているとき
        if(isset($_POST["answer505"]) && isset($_POST["a505"])){

          if($answerVal == "B"){
            $ans505 = true;
           
            $sql = "insert into question values ('$mId','$qId505','$ans505')";
           
            $stmt = $pdo->query($sql);
              // echo "接続してる１";
  
          }elseif($answerVal != "B"){
            $ans505 = false;
            $sql = "insert into question values ('$mId','$qId505','$ans505')";
            $stmt = $pdo->query($sql);
              // echo "接続してる2";
          }

        }


      }elseif($count == 1){//レコードありの場合
        // echo "反映されてる２";

        if(isset($_POST["answer505"]) && isset($_POST["a505"])){

          if($answerVal == "B"){
            $ans505 = true;
            
            $sql = "update question set answer = '$ans505' where id = '$mId' and question_id = '$qId505'";
            $stmt = $pdo->query($sql);
              // echo "接続してる3";
  
          }elseif($answerVal != "B"){
            $ans505 = false;
          
            $sql = "update question set answer = '$ans505' where id = '$mId' and question_id = '$qId505'";
            $stmt = $pdo->query($sql);
              // echo "接続してる4";
          }

        }

      }

      // $pdo = null;

    }catch(PDOException $e){
      exit('データベース接続失敗'.$e->getMessage());
    }

    ?>

<?php 
        // 確認用、コメントアウトしてね。
        $qId = $_POST["question_id505"];

        if(isset($answerVal)){
          // echo "<br>\n";
          if($answerVal == "B"){
            // echo "正解！！";
          }elseif($answerVal == "A" || $answerVal == "C" || $answerVal == "D"){
            // echo "不正解・・・";
          }else{
            //何も表示しない
          }
          $_SESSION["answer505"] = $_POST["answer505"];

        }

    ?>

    <?php


      if(is_null($_SESSION["answer505"])){
        $_SESSION["answer505"] = "E";
      }

      if($_SESSION["answer505"] == "B"){
        echo "<div class='maru'>◎</div>";
      }else if ($_SESSION["answer505"] == "E"){
        
      }else{
        echo "<div class='batu'>✖</div>";
      }
    ?>


    <div class="footer">
      <div class="inner">
       

        <div class="button-container">
          <!-- 後でリンクを貼っていく！ -->
          <!-- ---------------------------------------------------------local ---------------------------------->

          <button onclick="location.href='../question/question04HTMLkunii.php'" id="beforeButton"><<　前の問題へ</button>
          <button onclick="location.href='../main/mainHTML.php'" id="mainButton">一覧に戻る</button>
          <!-- <form method="post" action="URL">
            <button onclick="location.href='../login/gakusyulogin.php'">ログアウト</button>
            <?php //session_destroy($_SESSION['id']);?>
          </form> -->
         
          <button onclick="location.href='../question/question06HTML.php'" id="nextButton">次の問題へ　>></button>
          <!-- ---------------------------------------------------------local ------------------------------------->
        </div>

        <div class="preview">
          <?php
          // 正解数の表示
          $totalQuestion;
          $correctAnswer;

          $totalQuestionSql;
          $correctAnswerSql;

          try{
            $pdo = new PDO($dsn,$db_user,$db_pass);

            // 問題数

            $totalQuestionSql = "select count(distinct question_id) from question where question_id >= 500 and question_id < 600 and id = '$mId'";
            $stmt = $pdo->query($totalQuestionSql);
            $totalQuestion = $stmt->fetchColumn();
            //var_dump((int)$totalQuestion); //←←string型が取れているのでキャストが必要
            

            //正解数

            $correctAnswerSql = "select count(answer) from question where question_id >= 500 and question_id < 600 and id = '$mId' and answer = true";
            $stmt = $pdo->query($correctAnswerSql);
            $correctAnswer = $stmt->fetchColumn();
            //var_dump((int)$correctAnswer);//←←string型が取れているのでキャストが必要
            
            // echo "正解数：".$correctAnswer."<br>\n"."解答した問題数：".$totalQuestion."<br>\n";
            echo "正解数：".$correctAnswer."／";

            // $pdo = null;
          }catch(PDOException $e){
            exit('データベース接続失敗'.$e->getMessage());

          }

          ?>

          <script type="text/javascript">
            document.write(document.forms.length+"<\/em>");
          </script>

          <!-- <script type="text/javascript">
            document.write("総合問題数："+document.forms.length+"<\/em>");
          </script> -->

        </div>

        <!-- <div class="top-bar"> -->
          <div class="logout-button">
          <form action="" method="GET">
            <input type="submit" id="logoutbtn"name="logout" value="ログアウト">
        </form>
        <!-- ↓のbuttonの書き方だとセッションがdestroyされないので、書き直し -->
          <!-- <button id="logoutbtn" name="logout" onclick="location.href='http:\/\/marrotech.php.xdomain.jp/site/login/gakusyulogin.php'">ログアウト</button> -->
        </div>

        <!-- ログアウト処理が無いと、ブラウザの戻るで戻れて、セッションがdestroyされない -->
        <?php
        if(isset($_GET["logout"])){
          session_destroy();//セッションデータの破棄
          header("Location: ../login/gakusyulogin.php");
        }
        ?>
        <!-- formとかの要素数を数えられる -->
        <!-- <script type="text/javascript">
          // document.write("Javaアプレット数 = <em>"+document.applets.length+"<\/em><br \/>");
          // document.write("プラグイン数 = <em>"+document.embeds.length+"<\/em><br \/>");
          // document.write("embed数 = <em>"+document.plugins.length+"<\/em><br \/>");
          // document.write("画像数 = <em>"+document.images.length+"<\/em><br \/>");
          // document.write("リンク数 = <em>"+document.links.length+"<\/em><br \/>");
          // document.write("アンカー数 = <em>"+document.anchors.length+"<\/em><br \/>");
          // document.write("フォーム数 = <em>"+document.forms.length+"<\/em>");
        </script> -->

      </div>
    </div>

    <?php
        //DB閉じる
        $pdo = null;
        ?>

        <!-- JavaScriptの追加 -->
        <script src="ohmoriJS.js">
        </script>

</body>

</html>