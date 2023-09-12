//まずドキュメント（html）の中で　例えば　<button id = "myButton"　みたいなやつを探します
//それがクリックしたときに　以下のfunction処理をするよってのが以下の一行です
document.getElementById("mainButton").onclick = function() {
  //そしてlocation.hrefで遷移先を　= "指定のURL"にっするよっていう内容です
  location.href = ' ../main/mainHTML.php';
};

document.getElementById("beforeButton").onclick = function() {
  location.href = ' ../question/question01HTML.php';
};

document.getElementById("nextButton").onclick = function() {
  location.href = ' ../question/question03HTMLsato.php';
};

//解答ボタンにid付与しDOMを取得
let ans201btn = document.getElementById('idou201');
let ans202btn = document.getElementById('idou202');
let ans203btn = document.getElementById('idou203');
let ans204btn = document.getElementById('idou204');
let ans205btn = document.getElementById('idou205');

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
ans201btn.addEventListener('click', function() {
  localStorage.setItem('scrollPosition', window.scrollY);
});
ans202btn.addEventListener('click', function() {
  localStorage.setItem('scrollPosition', window.scrollY);
});
ans203btn.addEventListener('click', function() {
  localStorage.setItem('scrollPosition', window.scrollY);
});
ans204btn.addEventListener('click', function() {
  localStorage.setItem('scrollPosition', window.scrollY);
});
ans205btn.addEventListener('click', function() {
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