<body>
  <?php
  include("check.php");
  ?>
  <div class="main" tabindex="0">
  <div class="slideshow-container">
  <?php

  $result = mysqli_query($connect,"select title from news");

  // Начало раздела "Новости"
  for($i=1;$i<=3;$i++){
    $row = $result->fetch_assoc();
    ?>
      <div class="mySlides fade">
          <a href ="news.php?id_new=<?php echo $i?>" method=post>
              <img src="img/news<?=$i?>.jpg" style="width:100%">
              <?php $_SESSION['news1'] = 1; ?>
              
              <div class="text"><?php echo $row['title'] ?></div>
          </a>
      </div><?php
  }?>

    <!-- Кнопки влево/вправо у новостей -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
  </div>
  <br>

  <!-- Точки под картинками -->
  <div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
  </div>
  <?php
  //Конец раздела "Новости"

  weightper();

  $title = "Хиты продаж";
  $query = "select product.id_p, product.name, product.price, product.manufacturer, product.action, product.category,round(avg(rate.rate),2) rate from product,purchase,rate where product.id_p=purchase.id_p and product.id_p=rate.id_p group by product.id_p order by count(purchase.id_pur) DESC LIMIT 9;";
  blockprint($title,$query);

  $title = "Самые просматриваемые";
  $query = "select product.id_p, product.name, product.price, product.manufacturer, product.action, product.category,round(avg(rate.rate),2) rate from product,visits,rate where product.id_p=visits.id_p and product.id_p=rate.id_p group by product.id_p order by count(visits.id_v) DESC LIMIT 9;";
  blockprint($title,$query);

  $title = "Лучшие оценки";
  $query = "select product.id_p, product.name, product.price, product.manufacturer, product.action, product.category, round(avg(rate.rate),2) rate from product,rate where product.id_p=rate.id_p group by id_p order by avg(rate.rate) DESC LIMIT 9";
  blockprint($title,$query);

  viewed();
  infobar();
  ?>
  </div>

  <script>
    var slideIndex = 1;
    showSlides(slideIndex);

    // Next/previous controls
    function plusSlides(n) {
      showSlides(slideIndex += n);
    }

    // Thumbnail image controls
    function currentSlide(n) {
      showSlides(slideIndex = n);
    }

    function showSlides(n) {
      var i;
      var slides = document.getElementsByClassName("mySlides");
      var dots = document.getElementsByClassName("dot");
      if (n > slides.length) {slideIndex = 1}
      if (n < 1) {slideIndex = slides.length}
      for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
      }
      for (i = 0; i < dots.length; i++) {
          dots[i].className = dots[i].className.replace(" active", "");
      }
      slides[slideIndex-1].style.display = "block";
      dots[slideIndex-1].className += " active";
    }
  </script>

</body>