  //解答ボタンにid付与しDOMを取得
  //実際は601-605を取得しているよ、リファクタするべきだけどね
  let ans401btn = document.getElementById('ans601');
  let ans402btn = document.getElementById('ans602');
  let ans403btn = document.getElementById('ans603');
  let ans404btn = document.getElementById('ans604');
  let ans405btn = document.getElementById('ans605');

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
  ans401btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans402btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans403btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans404btn.addEventListener('click', function() {
      localStorage.setItem('scrollPosition', window.scrollY);
  });
  ans405btn.addEventListener('click', function() {
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
