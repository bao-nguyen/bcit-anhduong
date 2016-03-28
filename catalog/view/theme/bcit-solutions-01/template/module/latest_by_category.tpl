<div class="bcit-title">
    <h3 class="home-title" style=""><?php echo $heading_title; ?></h3>
</div>


<div id="bcit-news-bycategory">
    <ul>
        <?php foreach ($newss as $news) { ?>
            <li><a href="<?php echo $news['href']; ?>"><i class="fa fa-angle-right"></i><?php echo $news['name']; ?></a></li>
        <?php } ?>
    </ul>    
</div>


<script type="text/javascript"><!--

$('#bcit-news-bycategory<?php echo $module; ?>').slick({
        // dots: true,
        infinite: true,
        autoplay: false,
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
