<?php
session_start();
ob_start();
if (isset($_POST["logout"])) {
  session_destroy();
  //---------------------------------------------------------------------
  header("Location: ../login/gakusyulogin.php");
  //-------------------------------------------------------------
}
//↑logout押下時
//DB接続情報-----------------------------------------ローカル環境
$dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
$db_user = "testuser";
$db_pass = "n8f]-j3&evUEWs)";
//---------------------------------------------
//初期化
$id;
$nickname;

//各問題正答数初期化
$result101;
$result201;
$result301;
$result401;
$result501;
$result601;

//用意した問題数
$MaxQuesNum = 5;

//配列初期化
$countSQL = array();

for ($i = 1; $i <= 6; $i++) {
  $startQuestionId = ($i * 100) + 1;
  $endQuestionId = ($i * 100) + 5;
  //配列countSQlに101以下のキーを格納して保存
  $countSQL[$i . "01To"] = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between $startQuestionId and $endQuestionId";
}
//ベタ打ちはきれいじゃない、戒めとして残す
// $countSQL101To = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 101 and 105";
// $countSQL201To = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 201 and 205";
// $countSQL301To = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 301 and 305";
// $countSQL401To = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 401 and 405";
// $countSQL501To = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 501 and 505";
// $countSQL601To = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 601 and 605";

//汎用count_answerd function
function executeSelectCountAnswer($pdo, $key, $id)
{
  //DBで正答数をカウント処理
  //global変数
  //あとここから問題数取得表示、問題数MAXの時、echoでスパンクラス表示処理
  global $countSQL; //
  $sql = $countSQL[$key];
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(1, $id);

  $stmt->execute();
  //rowcolumnはselectには使えない！！

  return $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8" />
  <title>Learning main Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="mainPageStyles.css" media="all" />
</head>

<body>

  <div class="header">
    <div class="inner">
      <h1>WELCOME TO PHP LEARNING PAGE</h1>
      <p>
        <?php //$_seesion からuserIDを取得してechoする
        if ($_SESSION["loggedIn"] == true) {
          $id = $_SESSION["id"];
          //echo "セッションスコープに保存してあるidの値は" . $id;
          //DB処理
          try {
            $pdo = new PDO($dsn, $db_user, $db_pass);
            $sql = "select nickname from `user_login` where id = ?";
            //プレースホルダ :$idとかが一般的だが、?でやる（順番指定）
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(1, $id);

            //クエリ実行
            $stmt->execute();

            foreach ($stmt as $rec) {
              $nickname = $rec["nickname"];
            }
            //ニックネーム取得表示
            echo $nickname;


            //正答数取得
            $result101 = executeSelectCountAnswer($pdo, "101To", $id);
            $result201 = executeSelectCountAnswer($pdo, "201To", $id);
            $result301 = executeSelectCountAnswer($pdo, "301To", $id);
            $result401 = executeSelectCountAnswer($pdo, "401To", $id);
            $result501 = executeSelectCountAnswer($pdo, "501To", $id);
            $result601 = executeSelectCountAnswer($pdo, "601To", $id);
            //  echo $result401;

          } catch (PDOException $e) {
            echo "DB接続失敗";
            echo $e->getMessage();
          }

            //青木がしつれいします：）　ありがとうございます！　助かりました＆確認しました
            $totalCorrect; //総合正解数
            $message; //ユーザーへのメッセージ
            try{
              $pdo = new PDO($dsn, $db_user, $db_pass);
              $sqlA = "select count(*) as num from `question` where id = $id and answer = true";//総合正解数の検索
              $stmt = $pdo -> query($sqlA);
              $totalCorrect = $stmt -> fetchColumn();
              $pdo = null;
            }catch(PDOException $e){
              echo "DB接続失敗";
              echo $e->getMessage();
            }
        
            if($totalCorrect == 0){
              $message = "こんにちは！一緒に頑張りましょう！";
            }else if($totalCorrect <= 10){
              $message = "phpは分かってきましたか？まだまだ頑張りましょう！";
            }else if($totalCorrect <= 20){
              $message = "半分くらい進んできたところですか？焦らず楽しみましょう”";
            }else if($totalCorrect <= 24){
              $message = "楽しくなってきましたね！さらに上をめざして頑張りましょう！";
            }else if($totalCorrect <= 29){
              $message = "phpは楽しいですか？ゴールまであと一息です！";
            }else if($totalCorrect == 30){
              $message = "完璧です！おつかれさまでした！";
            }        
            // echo $totalCorrect;
            // echo $message;
            //失礼しました！
        }
        //ローカル時はコメントアウト
        else {
          //ログインしてなかったらログインページへ！-------------------------------
          header("Location: ../login/gakusyulogin.php");
          //----------------------------------------
        }
        ?>
        さん
        <?php
          echo " ".$message." 現在のスコアは・・・30門中、".$totalCorrect."門です！";
        ?>
      </p>
    </div>
  </div>



  <div class="mainContent">
    <div class="button-container">

      <div class="row">
        <span class="checkbox-wrapper">
          <input type="checkbox" id="checkbox1" name="checkbox" value="1" disabled>
          <label for="checkbox1" id="label1">
          <?php
            if($result101 == $MaxQuesNum){ 
              echo "<span class='checked-mark'>✓</span>";
            } 
            ?>
            </label>
        </span>
        <div class="numLogo">1</div>
        <!-- ---------------------------------------------------- -->
        <button class="linkbtn" onclick="location.href='../question/question01HTML.php'">日付に関する問題</button>
        <!-- ----------------------------------------------------- -->
        <div class="numLogo">
          <?php
          echo $result101;
          ?>/
          <?php
          echo $MaxQuesNum;
          ?>
        </div>
      </div>

      <div class="row">
        <span class="checkbox-wrapper">
          <input type="checkbox" id="checkbox2" name="checkbox" value="1" disabled>
          <label for="checkbox2" id="label2">
          <?php
            if($result201 == $MaxQuesNum){ 
              echo "<span class='checked-mark'>✓</span>";
            } 

            ?>
                        </label>
        </span>
        <div class="numLogo">2</div>
         <!-- ----------------------------------------------------- -->
        <button class="linkbtn" onclick="location.href='../question/question02HTMLaoki.php'">配列に関する問題</button>
         <!-- ----------------------------------------------------- -->
        <div class="numLogo">
          <?php
           echo $result201;
          ?>/
          <?php
          echo $MaxQuesNum;
          ?>
        </div>
      </div>

      <div class="row">
        <span class="checkbox-wrapper">
          <input type="checkbox" id="checkbox3" name="checkbox" value="1" disabled>
          <label for="checkbox3" id="label3">
          <?php
            if($result301 == $MaxQuesNum){ 
              echo "<span class='checked-mark'>✓</span>";
            } 
            ?>
            </label>
        </span>
        <div class="numLogo">3</div>
         <!-- ----------------------------------------------------- リンク先ng　たぶん問題ページに問題あり-->
        <button class="linkbtn" onclick="location.href='../question/question03HTMLsato.php'">文字列操作に関する問題</button>
         <!-- ----------------------------------------------------- -->
        <div class="numLogo">
          <?php
          echo $result301;
          ?>/
          <?php
          echo $MaxQuesNum;
          ?>
        </div>
      </div>

      <!--ココ参考にしてください↓-->
      <div class="row">
        <span class="checkbox-wrapper">
          <input type="checkbox" id="checkbox4" name="checkbox" value="1" disabled>
          <label for="checkbox4" id="label4">
            <?php
            if($result401 == $MaxQuesNum){ 
              echo "<span class='checked-mark'>✓</span>";
            } 
            ?>
            </label>
        </span>
        <div class="numLogo">4</div>
         <!-- ----------------------------------------------------- リンク先おｋ-->
        
        <button class="linkbtn" onclick="location.href='../question/question04HTMLkunii.php'">GET,POSTリクエストに関する問題</button>
         <!-- ----------------------------------------------------- -->
        <div class="numLogo">
          <?php
          echo $result401;
          ?>/
          <?php
          echo $MaxQuesNum;
          ?>
        </div>

    </div>

    <div class="row">
      <span class="checkbox-wrapper">
        <input type="checkbox" id="checkbox5" name="checkbox" value="1" disabled>
        <label for="checkbox5" id="label5">
        <?php
            if($result501 == $MaxQuesNum){ 
              echo "<span class='checked-mark'>✓</span>";
            } 
            ?>
            </label>
      </span>
      <div class="numLogo">5</div>
       <!-- ----------------------------------------------------- -->
      <button class="linkbtn" onclick="location.href='../question/question05HTMLohmori.php'">If, &&, xor, is_numrec()<br/>などの論理問題</button>
       <!-- ----------------------------------------------------- -->
      <div class="numLogo">
        <?php
           echo $result501;
        ?>/
        <?php
          echo $MaxQuesNum;
        ?>
      </div>
    </div>

    <div class="row">
      <span class="checkbox-wrapper">
        <input type="checkbox" id="checkbox6" name="checkbox" value="1" disabled>
        <label for="checkbox6" id="label6">
        <?php
            if($result601 == $MaxQuesNum){ 
              echo "<span class='checked-mark'>✓</span>";
            } 
            ?>
            </label>
      </span>
      <div class="numLogo">6</div>
       <!-- ----------------------------------------------------- -->
      <button class="linkbtn" onclick="location.href='../question/question06HTML.php'">for,while,do-while,foreach<br/>などのループ処理問題</button>
       <!-- ----------------------------------------------------- -->
      <div class="numLogo">
        <?php
          echo $result601;
        ?>/
        <?php
          echo $MaxQuesNum;
        ?>
      </div>
    </div>
  </div>
  </div>

  <div class="footer">
    <div class="inner">
      <form action="" method="post">
        <button type="submit" name="logout"　id="logout">ログアウト</button>
      </form>
    </div>
  </div>

  <script>
  
  </script>
</body>

</html>