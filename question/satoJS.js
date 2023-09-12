  //解答ボタンにid付与しDOMを取得
  let ans301btn = document.getElementById('ans301');
  let ans302btn = document.getElementById('ans302');
  let ans303btn = document.getElementById('ans303');
  let ans304btn = document.getElementById('ans304');
  let ans305btn = document.getElementById('ans305');
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
  ans301btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans302btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans303btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans304btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans305btn.addEventListener('click', function() {
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
