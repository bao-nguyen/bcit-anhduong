

<div id="bcit-slideshow<?php echo $module; ?>" class="col-sm-12" style="opacity: 1; padding:0;">
    <?php foreach ($banners as $banner) { ?>
        <div class="item">
            <?php if ($banner['link']) { ?>
                <a href="<?php echo $banner['link']; ?>"><img  src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" /></a>
            <?php } else { ?>
                <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive" style="margin:0 !important; padding:0 !important"/>
            <?php } ?>
        </div>
    <?php } ?>
</div>

  <script type="text/javascript">
    $(document).ready(function(){
      $('#bcit-slideshow<?php echo $module; ?>').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
      });
    });
  </script>
  
