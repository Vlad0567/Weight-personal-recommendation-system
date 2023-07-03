window.addEventListener('load', function() {
    var startTime = Math.floor(Date.now()); // Получение текущего времени в милисекундах
  
    window.addEventListener('beforeunload', function() {
      var endTime = Math.floor(Date.now()); // Получение текущего времени в милисекундах
      var duration = endTime - startTime;
  
      // Отправка данных на сервер с помощью AJAX-запроса
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'addDur.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.send('duration=' + duration);
    });
});



  

