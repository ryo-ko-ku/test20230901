  //解答ボタンにid付与しDOMを取得
  let ans501btn = document.getElementById('a501');
  let ans502btn = document.getElementById('a502');
  let ans503btn = document.getElementById('a503');
  let ans504btn = document.getElementById('a504');
  let ans505btn = document.getElementById('a505');

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
  ans501btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans502btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans503btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans504btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans505btn.addEventListener('click', function() {
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
