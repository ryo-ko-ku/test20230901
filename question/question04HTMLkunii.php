<?php
session_start();
ob_start();

//初期化DB用
$id;
//レコードがあるか
$rowCount;
//汎用select
$selectSql = "select * from `question` where id = ? and question_id = ?";
$insertSql = "insert into `question` (id,question_id,answer) values(?,?,?)";
$updateSql = "update `question` set id = ?, question_id = ?, answer = ? where id = ? and question_id = ?";

//DB接続情報----------------------------local
$dsn = "mysql:host=localhost;dbname=test202309;charset=utf8";
$db_user = "testuser";
$db_pass = "n8f]-j3&evUEWs)";
//-------------------------------

//include file 
if ($_SESSION["loggedIn"]) {
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
    //リダイレクト---------------------local
    header("Location: ../login/gakusyulogin.php");
    //-----------------------
}

?>

<!DOCTYPE html>
<html lang="ja">


<head>
    <meta charset="utf-8" />
    <title>4.GETリクエスト POSTリクエスト</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="ansPageStyles04.css" media="all" /> 
</head>

<body>

    <div class="top-bar">
        <form action="" method="GET">
            <input type="submit" value="ログアウト" id="logoutbtn" name="logout">

        </form>
    </div>

    <div class="header">
        <div class="inner">
            <h1>4.GETリクエスト POSTリクエストに関する問題</h1>
            参考:<a href="https://wepicks.net/phpref-get/" target="_blank">：PHP $_GET（ゲット変数）のすべて！【初心者向け基本】</a><br />
            参考:<a href="https://wepicks.net/phpref-post/" target="_blank">：PHP $_POST（ポスト変数）のすべて！【初心者向け基本】</a>

        </div>
    </div>
    <div class="mainContent">

        <div class="row">
            <h2>【問題１】GET変数の説明について誤っている選択肢を選びなさい</h2>
            <form action="" method="POST">
                <input type="hidden" name="question_id401" value="401">
                <p>問: 以下の選択肢から誤っているものを選んで解答ボタンを押してください。</p>
                <input type="radio" name="answer401" value="A" id="optionA">
                <label for="optionA">$_GET のデータ型は配列（配列変数）で 配列として使用する</label><br />
                <input type="radio" name="answer401" value="B" id="optionB">
                <label for="optionB">$_GET に渡される値は、自動的にurldecode()関数を介している</label><br />
                <input type="radio" name="answer401" value="C" id="optionC">
                <label for="optionC">$_GET は HTTP GET メソッド で送信され、URLパラメーターとして送られてきた値を取得する変数である</label><br />
                <input type="radio" name="answer401" value="D" id="optionD">
                <label for="optionD">$_GETは、スーパーグローバル変数なので、スクリプトのコード中どこからでも使用することが出来る変数である</label><br />
                <input type="submit" value="解答する" name="ans401" id="ans401">
            </form>
        </div>
        <?php
        $flag;
        // $_SESSION["answer401"];
        //当該問題の値と解答ボタンが押されたら正誤判定処理
        if (isset($_POST["ans401"]) && isset($_POST["answer401"])) {
            $answerVal401 = $_POST["answer401"];
            $_SESSION["answer401"] = $_POST["answer401"];

            //問題id取得
            $question_id = $_POST["question_id401"];

            //以下DB処理

            $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
            // echo "<h1>" . $rowCount . "</h1>";

            //正解フラグ

            $resultStr;
            //update処理
            if ($rowCount == 1) {
                if ($answerVal401 == "A") {
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
                if ($answerVal401 == "A") {
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
        if (is_null($_SESSION["answer401"])) {
            $_SESSION["answer401"] = "E";
        }

        if ($_SESSION["answer401"] == "A") {
            echo "<h1 class='maru'>○</h1>";
        } else if ($_SESSION["answer401"] == "E") {
        } else {
            echo "<h1 class='batu'>×</h1>";
        }
        ?>
  
        <div class="row">
            <h2>【問題２】GETメソッドでデータ送信するURLパラメータについて正しいコードを選びなさい</h2>
            <form action="" method="POST">
                <input type="hidden" name="question_id402" value="402">
                <p>問: 以下の選択肢から正しいものを選んで解答ボタンを押してください。</p>
                <input type="radio" name="answer402" value="A" id="optionA">
                <label for="optionA">http://sample.com/index.php?key名=値&key名=値&key名=値・・</label><br>
                <input type="radio" name="answer402" value="B" id="optionB">
                <label for="optionB">http://sample.com/index.php%key名=値?key名=値?key名=値・・</label><br>
                <input type="radio" name="answer402" value="C" id="optionC">
                <label for="optionC">http://sample.com/index.php&key名=値&key名=値&key名=値・・</label><br>
                <input type="radio" name="answer402" value="D" id="optionD">
                <label for="optionD">http://sample.com/index.php%key名=値&key名=値&key名=値・・</label><br>
                <input type="submit" value="解答する" name="ans402" id="ans402">
            </form>
            <?php
            if (isset($_POST["ans402"]) && isset($_POST["answer402"])) {
                $answerVal402 = $_POST["answer402"];
                $_SESSION["answer402"] = $_POST["answer402"];
                //問題id取得
                $question_id = $_POST["question_id402"];

                //以下DB処理

                $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
                // echo "<h1>" . $rowCount . "</h1>";

                //正解フラグ
                $flag;
                //update処理
                if ($rowCount == 1) {
                    if ($answerVal402 == "A") {
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
                    if ($answerVal402 == "A") {
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
            if (is_null($_SESSION["answer402"])) {
                $_SESSION["answer402"] = "E";
            }

            if ($_SESSION["answer402"] == "A") {
                echo "<h1 class='maru'>○</h1>";
            } else if ($_SESSION["answer402"] == "E") {
            } else {
                echo "<h1 class='batu'>×</h1>";
            }
            ?>

        </div>

        <div class="row">
            <h2>【問題３】POST変数の説明について誤っている選択肢を選びなさい</h2>
            <form action="" method="POST">
                <input type="hidden" name="question_id403" value="403">
                <p>問: 以下の選択肢から誤っているものを選んで解答ボタンを押してください。</p>
                <input type="radio" name="answer403" value="A" id="optionA">
                <label for="optionA">$_POST は HTTP POST で渡された値を取得する変数である</label><br>
                <input type="radio" name="answer403" value="B" id="optionB">
                <label for="optionB"> HTTP POST メソッドでデータを送信する方法はHTML の &lt;form&gt;タグ の 属性 を POST にする</label><br>
                <input type="radio" name="answer403" value="C" id="optionC">
                <label for="optionC">$_POST は 連想配列として使用する</label><br>
                <input type="radio" name="answer403" value="D" id="optionD">
                <label for="optionD">HTTP POST で送信されたリクエスト結果の値は変化しない</label><br>
                <input type="submit" value="解答する" name="ans403" id="ans403">
            </form>
            <?php
            if (isset($_POST["ans403"]) && isset($_POST["answer403"])) {
                $answerVal403 = $_POST["answer403"];
                $_SESSION["answer403"] = $_POST["answer403"];
                //問題id取得
                $question_id = $_POST["question_id403"];

                //以下DB処理

                $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
                // echo "<h1>" . $rowCount . "</h1>";

                //正解フラグ
                $flag;
                //update処理
                if ($rowCount == 1) {
                    if ($answerVal403 == "D") {
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
                    if ($answerVal403 == "D") {
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
            if (is_null($_SESSION["answer403"])) {
                $_SESSION["answer403"] = "E";
            }

            if ($_SESSION["answer403"] == "D") {
                echo "<h1 class='maru'>○</h1>";
            } else if ($_SESSION["answer403"] == "E") {
            } else {
                echo "<h1 class='batu'>×</h1>";
            }
            ?>
        </div>

        <div class="row">
            <h2>【問題４】クライアントからHTTP POSTで送信されたリクエスト結果の値を表示する場合、正しいコードを選びなさい</h2>
            <form action="" method="POST">
                <input type="hidden" name="question_id404" value="404">
                <p>問: 以下の選択肢から正しいものを選んで解答ボタンを押してください。</p>
                <input type="radio" name="answer404" value="A" id="optionA">
                <label for="optionA">&lt;?php echo $_POST[sample?]; ?&gt;</label><br>
                <input type="radio" name="answer404" value="B" id="optionB">
                <label for="optionB">&lt;?php echo $_POST[sample]; ?&gt;</label><br>
                <input type="radio" name="answer404" value="C" id="optionC">
                <label for="optionC">&lt;?php echo $_POST['sample']; ?&gt;</label><br>
                <input type="radio" name="answer404" value="D" id="optionD">
                <label for="optionD">&lt;?php echo $_POST[$sample]; ?&gt;</label><br>
                <input type="submit" value="解答する" name="ans404" id="ans404">
            </form>
            <?php
            if (isset($_POST["ans404"]) && isset($_POST["answer404"])) {
                $answerVal404 = $_POST["answer404"];
                $_SESSION["answer404"] = $_POST["answer404"];
                //問題id取得
                $question_id = $_POST["question_id404"];

                //以下DB処理

                $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
                // echo "<h1>" . $rowCount . "</h1>";

                //正解フラグ
                $flag;
                //update処理
                if ($rowCount == 1) {
                    if ($answerVal404 == "C") {
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
                    if ($answerVal404 == "C") {
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

            if (is_null($_SESSION["answer404"])) {
                $_SESSION["answer404"] = "E";
            }

            if ($_SESSION["answer404"] == "C") {
                echo "<h1 class='maru'>○</h1>";
            } else if ($_SESSION["answer404"] == "E") {
            } else {
                echo "<h1 class='batu'>×</h1>";
            }
            ?>
        </div>

        <div class="row">
            <h2>【問題５】formタグを利用してHTTPメソッドでデータ送信を行う時に指定する属性はどれか、当てはまるものを選びなさい</h2>
            <form action="" method="POST" id="myForm">
                <input type="hidden" name="question_id405" value="405">
                <p>問: 以下の選択肢から当てはまるものを選んで解答ボタンを押してください。</p>
                <input type="radio" name="answer405" value="A" id="optionA">
                <label for="optionA">input</label><br>
                <input type="radio" name="answer405" value="B" id="optionB">
                <label for="optionB">action</label><br>
                <input type="radio" name="answer405" value="C" id="optionC">
                <label for="optionC">method</label><br>
                <input type="radio" name="answer405" value="D" id="optionD">
                <label for="optionD">value</label><br>
                <input type="submit" value="解答する" name="ans405" id="ans405">
            </form>
            <?php
            if (isset($_POST["ans405"]) && isset($_POST["answer405"])) {
                $answerVal405 = $_POST["answer405"];
                $_SESSION["answer405"] = $_POST["answer405"];
                //問題id取得
                $question_id = $_POST["question_id405"];

                //以下DB処理

                $rowCount = executeSelect($pdo, $selectSql, $id, $question_id);
                // echo "<h1>" . $rowCount . "</h1>";

                //正解フラグ
                $flag;
                //update処理
                if ($rowCount == 1) {
                    if ($answerVal405 == "C") {
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
                    if ($answerVal405 == "C") {
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
            if (is_null($_SESSION["answer405"])) {
                $_SESSION["answer405"] = "E";
            }

            if ($_SESSION["answer405"] == "C") {
                echo "<h1 class='maru'>○</h1>";
            } else if ($_SESSION["answer405"] == "E") {
            } else {
                echo "<h1 class='batu'>×</h1>";
            }
            ?>
        </div>
    </div>




        <div class="footer">
            <div class="inner">


                <div class="button-container">
                    <!---ここ！-------------------- local---------------------->
                    <button class="formbtn" onclick="location.href='../question/question03HTMLsato.php'">＜＜前の問題へ</button>
                    <button id="listbtn" class="formbtn" onclick="location.href='../main/mainHTML.php'">一覧に戻る</button>
                    <button class="formbtn" onclick="location.href='../question/question05HTMLohmori.php'">次の問題へ＞＞</button>
                    <!-- ----------------------------------------------- -->
                </div>

                <div class="preview">
                    <?php
                    $quesMaxCount = 5;
                    echo "正当数:";

                    //これだとidの人の全問数だ
                    //between入れねば
                    $countSQL = "select count(*) as now_answer_result from `question` where id = ? and answer = true and question_id between 401 and 405";
                    $stmt = $pdo->prepare($countSQL);
                    $stmt->bindParam(1, $id);

                    $stmt->execute();
                    //rowcolumnはselectには使えない！！

                    $countAnsNum = $stmt->fetchColumn();
                    // foreach($stmt as $rec){
                    //     $countAnsNum++;
                    //     echo $countAnsNum;
                    // }

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
        <script src="kuniiJS.js">
        </script> 

</body>

</html>