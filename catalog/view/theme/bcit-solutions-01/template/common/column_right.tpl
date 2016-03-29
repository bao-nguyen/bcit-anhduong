<?php if ($modules) { ?>
<aside id="column-right" class="col-sm-3 hidden-xs">
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
  
  
       
    <div class="bcit-title">
        <h3 class="home-title" style=""><?php echo "Fanpage"; ?></h3>
    </div>
    <div id="bcit-news-bycategory" >
        <div class="fb-page"                 
                data-href="https://www.facebook.com/Trung-tâm-Ngoại-ngữ-Ánh-Dương-Anh-Duong-Language-Center-203133863383707"
                data-width="340" 
                data-hide-cover="false"
                data-show-facepile="true" 
                data-show-posts="false"
                >
                
            </div>    
    </div>     
    <div class="bcit-title">
        <h3 class="home-title" style=""><?php echo "Bản đồ"; ?></h3>
    </div>
    <div id="bcit-news-bycategory" >
        <img class="img-responsive img-center" src="image/map/bando.png"/>    
    </div>
  
</aside>

<?php } ?>

