<?php echo $bcit_header; ?>

<?php if ($bcit_logo || $bcit_banner) { ?>
    <div class="row">
        <?php echo $bcit_logo; ?>
        <?php echo $bcit_banner; ?>
    </div>
<?php } ?>
<?php if ($bcit_menu) { ?>
    <?php echo $bcit_menu; ?>
<?php } ?>

<div class="container">
    <div class="row">
        
        <div id="content" class="col-sm-12">
            <?php echo $content_top; ?>
                        
            <?php echo $bcit_content2_left; ?>
            <?php echo $bcit_content2_right; ?>
            
            <?php echo $bcit_content3_left; ?>
            <?php echo $bcit_content3_right; ?>
            
            <?php echo $content_bottom; ?>
        </div>
       
    </div>
</div>
<?php echo $bcit_footer1; ?>
