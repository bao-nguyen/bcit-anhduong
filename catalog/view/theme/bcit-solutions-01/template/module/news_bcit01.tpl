<div class="bcit-title">
    <h3 class="home-title" style=""><?php echo $heading_title; ?></h3>
</div>


<div id="bcit-news-bycategory">
<?php foreach ($newss as $news) { ?>
   <a href="<?php echo $news['href']; ?>">
    <div class="col-xs-12 course-item">
        <div class="col-xs-8">            
            <div class="text-course">
                <?php echo $news['name']; ?>
            </div>            
        </div>
        <div class="col-xs-4">            
                <div class="img-course">
                    <img src="<?php echo $news['thumb']; ?>" alt="<?php echo $news['name']; ?>" class="img-responsive">
                </div>
        </div>
    
    </div>
    </a>
<?php } ?>

</div>

