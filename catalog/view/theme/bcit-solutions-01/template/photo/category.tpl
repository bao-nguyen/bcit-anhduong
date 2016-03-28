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
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      
      
<div id="bcit-news-bycategory">      
      <?php if ($albums) { ?>
        <ul>
            <?php foreach ($albums as $ablum) { ?>
                <li><a href="<?php echo $ablum['href']; ?>"><i class="fa fa-angle-right"></i><?php echo $ablum['name']; ?></a></li>
            <?php } ?>
        </ul>           
      <?php } ?>
 </div>    
     
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $bcit_footer; ?>
