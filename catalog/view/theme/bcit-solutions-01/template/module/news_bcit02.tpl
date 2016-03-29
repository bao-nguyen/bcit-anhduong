<div class="bcit-title">
    <h3 class="home-title" style=""><?php echo $heading_title; ?></h3>
</div>

<div id="bcit-news-bycategory">
<?php foreach ($newss as $news) { ?>
        <div class="block-item" style="width:100%; float:left; margin-bottom:5px">
            <a href="<?php echo $news['href']; ?>">
                <img src="<?php echo $news['thumb']; ?>" alt="<?php echo $news['name']; ?>" title="<?php echo $news['name']; ?>" class="img-responsive pull-left"/>
            </a>
            <h4><a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></h4>                                
            
        </div>
    <?php } ?>

</div>

