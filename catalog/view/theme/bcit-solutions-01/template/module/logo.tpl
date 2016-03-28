<div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $url; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive img-center" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $url; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>