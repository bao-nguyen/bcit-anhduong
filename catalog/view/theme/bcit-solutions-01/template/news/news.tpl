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
           
            <div class="fb-share-button" data-href="<?php echo($share_fb); ?>" data-layout="button"></div>   
            <br /><br />                   
            <?php echo $content; ?>                        
            
            <?php if ($downloads) { ?>
                
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                
                                <td class="text-left"><?php echo $column_name; ?></td>
                                <td class="text-left"><?php echo $column_size; ?></td>
                                <td class="text-left"><?php echo $column_date_added; ?></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($downloads as $download) { ?>
                                <tr>
                                    
                                    <td class="text-left"><?php echo $download['name']; ?></td>
                                    <td class="text-left"><?php echo $download['size']; ?></td>
                                    <td class="text-left"><?php echo $download['date_added']; ?></td>
                                    <td><a href="<?php echo $download['href']; ?>" data-toggle="tooltip" title="<?php echo $button_download; ?>" class="btn btn-primary"><i class="fa fa-cloud-download"></i></a></td>            
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
    			
                    
                    
   	            <?php } ?>
            
            
            <?php if ($tags) { ?>
                <p><?php echo $text_tags; ?>
                    <?php for ($i = 0; $i < count($tags); $i++) { ?>
                        <?php if ($i < (count($tags) - 1)) { ?>
                            <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
                        <?php } else { ?>
                            <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
                        <?php } ?>
                    <?php } ?>
                </p>
            <?php } ?>
            <?php if ($newss) { ?>
                <h3><?php echo $text_related; ?></h3>
                <div class="row">
                    <?php foreach ($newss as $news) { ?>
                        <ul>
                            <li><h4><a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></h4></li>
                        </ul>
                    <?php } ?>
                </div>
            <?php } ?>   
            
            
            
            
            
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>

<?php echo $bcit_footer; ?>
