

<?php if ($menus) { ?>    
        <nav id="menu" class="navbar">
            <div class="navbar-header">
                <span id="category" class="visible-xs"><?php echo $text_menu; ?></span>
                <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse" style="">
                <ul class="nav navbar-nav">
                    <?php foreach ($menus as $menu) { ?>
                        <?php if ($menu['children']) { ?>
                            <li class="dropdown">
                                <a href="<?php echo $menu['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $menu['name']; ?></a>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-inner">
                                            <?php foreach (array_chunk($menu['children'], ceil(count($menu['children']) / $menu['column'])) as $children) { ?>
                                                <ul class="list-unstyled">
                                                    <?php foreach ($children as $child) { ?>
                                                        <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </div>                                         
                                    </div>
                            </li>
                        <?php } else { ?>
                            <li><a href="<?php echo $menu['href']; ?>"><?php echo $menu['name']; ?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </nav>    
<?php } ?>

