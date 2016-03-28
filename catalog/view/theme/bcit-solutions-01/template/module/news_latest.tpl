<div class="bcit-block">
    <h3><?php echo $heading_title; ?></h3>
    <div class="block-content">
    <?php foreach ($newss as $news) { ?>
        <div class="block-item" >
            <a href="<?php echo $news['href']; ?>">
                <img src="<?php echo $news['thumb']; ?>" alt="<?php echo $news['name']; ?>" title="<?php echo $news['name']; ?>" class="img-responsive pull-left"/>
            </a>
            <h4><a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></h4>                                
            <hr>
        </div>
    <?php } ?>
    </div>  
</div>