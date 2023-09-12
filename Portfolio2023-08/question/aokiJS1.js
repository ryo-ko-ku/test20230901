//まずドキュメント（html）の中で　例えば　<button id = "myButton"　みたいなやつを探します
//それがクリックしたときに　以下のfunction処理をするよってのが以下の一行です
document.getElementById("mainButton").onclick = function() {
    //そしてlocation.hrefで遷移先を　= "指定のURL"にっするよっていう内容です
    location.href = ' ../main/mainHTML.php';
  };
  
  document.getElementById("nextButton").onclick = function() {
    location.href = ' ../question/question02HTMLaoki.php';
  };


//リロード時に最後に表示していた位置にスクロールする処理
//解答ボタンにid付与しDOMを取得
let ans101btn = document.getElementById('idou101');
let ans102btn = document.getElementById('idou102');
let ans103btn = document.getElementById('idou103');
let ans104btn = document.getElementById('idou104');
let ans105btn = document.getElementById('idou105');

// ボタンがクリックされたときの処理を定義
//引数y座標を渡すとy座標分ウィンドウがスクロール
function scrollToPosition(y) {
    window.scrollTo(0, y);
}
          
// ボタンがクリックされたときに現在のスクロール位置(y座標)をローカルストレージに保存
//ローカルストレージはユーザー（クライアント側の）データを保存する、アプリケーションスコープみたいなもの（厳密には違うけど）
//あえて命名するならユーザーのブラウザスコープ的な感じ
//ブラウザ上（クライアント側に）データを保存する為セキュリティ上のリスクは低い（サーバーまで来ない）
//ボタンクリック後、リロード先で使うため
ans101btn.addEventListener('click', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});
ans102btn.addEventListener('click', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});
ans103btn.addEventListener('click', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});
ans104btn.addEventListener('click', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});
ans105btn.addEventListener('click', function() {
    localStorage.setItem('scrollPosition', window.scrollY);
});

// ページがロードされたとき、ローカルストレージに保存されたスクロール位置があればそれにスクロールする
window.addEventListener('load', function() {
    //constは定数　いわゆるfinal
    const scrollPosition = localStorage.getItem('scrollPosition');
    if (scrollPosition) {
        scrollToPosition(scrollPosition);
        // ローカルストレージから保存したデータを削除（お片付け）
        localStorage.removeItem('scrollPosition');
    }
});
