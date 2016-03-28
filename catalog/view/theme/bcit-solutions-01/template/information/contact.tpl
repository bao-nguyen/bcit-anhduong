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
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                        
                            <strong><?php echo $store; ?></strong><br />                            
                            <?php echo $text_address; ?>                            
                            
                            <?php if ($geocode) { ?>
                                <a href="https://maps.google.com/maps?q=<?php echo urlencode($geocode); ?>&hl=<?php echo $geocode_hl; ?>&t=m&z=15" target="_blank" class="btn btn-info"><i class="fa fa-map-marker"></i> <?php echo $button_map; ?></a>
                            <?php } ?>
                            
                            <?php echo $text_telephone; ?><br />
                            <?php echo $text_email; ?>: <?php echo $email; ?><br />
                            <br />
                            
                                <img class="img-responsive img-center" src="image/map/bando.png"/>
                            
                            
                        </div>
                        <div class="col-sm-6">    
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <fieldset>
                                    <legend><?php echo $text_contact; ?></legend>
                                    <div class="form-group required">
                                        <label class="col-sm-3 control-label" for="input-name"><?php echo $entry_name; ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
                                            <?php if ($error_name) { ?>
                                                <div class="text-danger"><?php echo $error_name; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
                                            <?php if ($error_email) { ?>
                                                <div class="text-danger"><?php echo $error_email; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-3 control-label" for="input-enquiry"><?php echo $entry_enquiry; ?></label>
                                        <div class="col-sm-9">
                                            <textarea name="enquiry" rows="10" id="input-enquiry" class="form-control"><?php echo $enquiry; ?></textarea>
                                            <?php if ($error_enquiry) { ?>
                                                <div class="text-danger"><?php echo $error_enquiry; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php echo $captcha; ?>
                                </fieldset>
                                <div class="buttons">
                                    <div class="pull-right">
                                        <input class="btn btn-primary" type="submit" value="<?php echo $button_submit; ?>" />
                                    </div>
                                </div>
                            </form>                        
                        </div>
                    </div>
                </div>
            </div>
            
        
            
            
            
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>    


<?php echo $bcit_footer; ?>
