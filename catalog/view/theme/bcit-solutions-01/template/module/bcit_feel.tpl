<div class="bcit-title">
    <h3 class="home-title" style=""><?php echo $heading_title; ?></h3>
</div>

<div id="bcit-feel<?php echo $module; ?>" class="bcit-carousel">
    <?php foreach ($feels as $feel) { ?>
        <div>
            <div class="feel-item">
                <div class="quote" style="padding: 20px; background:#f2f2f2">                    
                    <p style="margin-bottom: 0; font-size: 14px; font-weight: 400; line-height: 21px; color: #444; position: relative; z-index: 10; font-style: italic; font-weight: 500;"><?php echo $feel['feel']; ?><br></p>
                </div>
                <div class="student" style="margin-top:20px">
                    <img src="<?php echo $feel['thumb']; ?>" alt="<?php echo $feel['name']; ?>" title="<?php echo $feel['name']; ?>" class="img-responsive pull-left" style="border: 2px solid #3498db; border-radius: 50%; width:100px; height:auto; padding: 2px;"/>
                    <h3 style="float:left; color: #555; font-size: 14px; font-weight: 700; line-height: normal; margin-top: 15px; padding-left: 20px;"><?php echo $feel['name']; ?>,<small style="font-size: 13px; line-height: 18px; color: #e74c3c; display: block;"><?php echo $feel['company']; ?></small></h3>
                </div>
                             
            </div>
        </div>
    <?php } ?>
</div>


<script type="text/javascript"><!--

$('#bcit-feel<?php echo $module; ?>').slick({
        // dots: true,
        infinite: true,
        autoplay: true,
        autoplaySpeed: 5000,
        speed: 500,        
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                // centerMode: true,

            }

        }, {
            breakpoint: 800,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                dots: true,
                infinite: true,

            }


        }, {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
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

