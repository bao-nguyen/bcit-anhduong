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
      <label class="control-label" for="input-search"><?php echo $entry_search; ?></label>
      <div class="row">
        <div class="col-sm-4">
          <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control" />
        </div>
        <div class="col-sm-4">
          <select name="ncategory_id" class="form-control">
            <option value="0"><?php echo $text_ncategory; ?></option>
            <?php foreach ($categories as $ncategory_1) { ?>
            <?php if ($ncategory_1['ncategory_id'] == $ncategory_id) { ?>
            <option value="<?php echo $ncategory_1['ncategory_id']; ?>" selected="selected"><?php echo $ncategory_1['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $ncategory_1['ncategory_id']; ?>"><?php echo $ncategory_1['name']; ?></option>
            <?php } ?>
            <?php foreach ($ncategory_1['children'] as $ncategory_2) { ?>
            <?php if ($ncategory_2['ncategory_id'] == $ncategory_id) { ?>
            <option value="<?php echo $ncategory_2['ncategory_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ncategory_2['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $ncategory_2['ncategory_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ncategory_2['name']; ?></option>
            <?php } ?>
            <?php foreach ($ncategory_2['children'] as $ncategory_3) { ?>
            <?php if ($ncategory_3['ncategory_id'] == $ncategory_id) { ?>
            <option value="<?php echo $ncategory_3['ncategory_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ncategory_3['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $ncategory_3['ncategory_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ncategory_3['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-sm-4">
          <label class="checkbox-inline">
            <?php if ($sub_ncategory) { ?>
            <input type="checkbox" name="sub_ncategory" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="sub_ncategory" value="1" />
            <?php } ?>
            <?php echo $text_sub_ncategory; ?></label>
        </div>
      </div>
      <p>
        <label class="checkbox-inline">
          <?php if ($description) { ?>
          <input type="checkbox" name="description" value="1" id="description" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="description" value="1" id="description" />
          <?php } ?>
          <?php echo $entry_description; ?></label>
      </p>
      <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary" />
      <h2><?php echo $text_search; ?></h2>
      <?php if ($newss) { ?>
      
      <div class="row">
        <div class="col-sm-8 hidden-xs">
          <div class="btn-group">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        
        <div class="col-sm-2 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-sm-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
            <?php if ($limits['value'] == $limit) { ?>
            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <br />
      <div class="row">
        <?php foreach ($newss as $news) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $news['href']; ?>"><img src="<?php echo $news['thumb']; ?>" alt="<?php echo $news['name']; ?>" title="<?php echo $news['name']; ?>" class="img-responsive" /></a></div>
            <div class="caption">
              <h4><a href="<?php echo $news['href']; ?>"><?php echo $news['name']; ?></a></h4>
              <p><?php echo $news['description']; ?></p>
              
              
            </div>
            
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      
      
      
            
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>
<script type="text/javascript"><!--
$('#button-search').bind('click', function() {
	url = 'index.php?route=news/search';

	var search = $('#content input[name=\'search\']').prop('value');

	if (search) {
		url += '&search=' + encodeURIComponent(search);
	}

	var ncategory_id = $('#content select[name=\'ncategory_id\']').prop('value');

	if (ncategory_id > 0) {
		url += '&ncategory_id=' + encodeURIComponent(ncategory_id);
	}

	var sub_ncategory = $('#content input[name=\'sub_ncategory\']:checked').prop('value');

	if (sub_ncategory) {
		url += '&sub_ncategory=true';
	}

	var filter_description = $('#content input[name=\'description\']:checked').prop('value');

	if (filter_description) {
		url += '&description=true';
	}

	location = url;
});

$('#content input[name=\'search\']').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('select[name=\'ncategory_id\']').on('change', function() {
	if (this.value == '0') {
		$('input[name=\'sub_ncategory\']').prop('disabled', true);
	} else {
		$('input[name=\'sub_ncategory\']').prop('disabled', false);
	}
});

$('select[name=\'ncategory_id\']').trigger('change');
--></script>
<?php echo $bcit_footer; ?>
