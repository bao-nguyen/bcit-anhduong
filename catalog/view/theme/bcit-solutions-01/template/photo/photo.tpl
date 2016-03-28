<?php echo $bcit_header; ?>
<?php if ($bcit_logo || $bcit_banner) { ?>
    <div class="row">
        <?php echo $bcit_logo; ?>
        <?php echo $bcit_banner; ?>
    </div>
<?php } ?>
<?php if ($bcit_menu) { ?>
    <div class="row">
        <?php echo $bcit_menu; ?>
    </div>
<?php } ?>

<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    
    <div class="row">
        <?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>">
            <?php echo $content_top; ?>
            
            
            
            
            <h1><?php echo $heading_title; ?></h1>                             
            
            <?php $tren = '<div class="slider slider-for">'; ?>
            <?php $duoi = '<div class="slider slider-nav">'; ?>
            
            <?php foreach ($photo_images as $photo_image) { ?>
        
            <?php
                $tren .='
                <div>
                    <img src="'.$photo_image['image'].'" class="img-responsive img-center" alt="">
                        
                    </div>';
                  
                    
                $duoi .='<div><img src="'.$photo_image['thumb'].'" class="img-responsive img-center" alt=""></div>';              
            ?>
            <?php } ?>
            
            <?php $tren .='</div>'; ?>
            <?php $duoi .='</div>'; ?>
            
            <div class="items-slider-container js-items-slider-container">
                <?php echo $tren; ?>
                 
                <?php echo $duoi; ?>
            </div>
           
  <script type="text/javascript">
jQuery(document).ready( function () {

   // Sliders

   //// Slider Top
  jQuery('.js-items-slider-container .slider-for').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      autoplay: true,
      autoplaySpeed: 4000,
      fade: true,
      asNavFor: '.js-items-slider-container .slider-nav'
  });
  jQuery('.js-items-slider-container .slider-nav').slick({
      slidesToShow: 10,
      slidesToScroll: 1,
      asNavFor: '.js-items-slider-container .slider-for',
      dots: true,
      centerMode: true,
      autoplay: true,
      autoplaySpeed: 4000,
      focusOnSelect: true,
      arrows: true,
      accessibility: true,
      onAfterChange: function (slide, index) {
        console.log("slider-nav change");
        console.log(this.$slides.get(index));
        jQuery('.current-slide').removeClass('current-slide');
        jQuery(this.$slides.get(index)).addClass('current-slide');
      },
      onInit: function (slick) {
        jQuery(slick.$slides.get(0)).addClass('current-slide');
      }
  });
});
  </script>

            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>

<?php echo $bcit_footer; ?>
