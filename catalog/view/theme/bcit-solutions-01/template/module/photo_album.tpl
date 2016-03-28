<div class="bcit-title">
    <h3 class="home-title" style=""><?php echo $heading_title; ?></h3>
</div>


<div id="bcit-news-bycategory">
<div id="bcit-photo<?php echo $module; ?>" class="col-sm-12" style="opacity: 1; padding:0;">
    <?php foreach ($banners as $banner) { ?>
        <div class="item">
            
                <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive img-center" style="margin:0 !important; padding:0 !important"/>
            
        </div>
    <?php } ?>
</div>

</div>






  <script type="text/javascript">
    $(document).ready(function(){
      $('#bcit-photo<?php echo $module; ?>').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 5000,
      });
    });
  </script>
  
