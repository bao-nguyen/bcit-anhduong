<h3 class="text-left home-title"><?php echo $heading_title; ?></h3>   
<div id="bcit-carousel0<?php echo $module; ?>" class="bcit-carousel">
    <?php foreach ($newss as $news) { ?>
        <div>
            <div class="news-item">
                    <div class="image">
                        <a href="<?php echo $news['href']; ?>">
                            <img style="margin:auto" src="<?php echo $news['thumb']; ?>" alt="<?php echo $news['name']; ?>" title="<?php echo $news['name']; ?>" class="img-responsive" />
                        </a>
                    </div>
                    <div class="caption">   
                        <h4><a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></h4>
                        <p><?php echo $news['description']; ?></p>
                    </div>                    
                </div>
        </div>
    <?php } ?>
</div>


<script type="text/javascript"><!--

$('#bcit-carousel0<?php echo $module; ?>').slick({
        // dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 3000,
        speed: 500,        
        slidesToShow: 4,
        slidesToScroll: 3,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 2,
                // centerMode: true,

            }

        }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 2,
                dots: true,
                infinite: true,

            }


        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                dots: true,
                infinite: true,
                
            }
        }, {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                infinite: true,
                autoplay: true,
                autoplaySpeed: 2000,
            }
        }]
    });
--></script>
