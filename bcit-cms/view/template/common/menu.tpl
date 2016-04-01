<ul id="menu">
    <li id="dashboard"><a href="<?php echo $home; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
    <li id="menu"><a href="<?php echo $menu; ?>"><i class="fa fa-bars fa-fw"></i><span><?php echo $text_menu; ?></span></a></li>
    <li id="news"><a class="parent"><i class="fa fa-newspaper-o fa-fw"></i> <span><?php echo $text_news; ?></span></a>
        <ul>
            <li><a href="<?php echo $cat; ?>"><?php echo $text_cat; ?></a></li>
            <li><a href="<?php echo $news; ?>"><?php echo $text_news; ?></a></li>
            <li><a href="<?php echo $viewpdf; ?>"><?php echo $text_viewpdf; ?></a></li>
            
        </ul>
    </li>		   
    <li id="page"><a href="<?php echo $information; ?>"><i class="fa fa-info fa-fw"></i><span><?php echo $text_information; ?></span></a></li>				
    <li id="feel"><a href="<?php echo $feel; ?>"><i class="fa fa-quote-right fa-fw"></i><span><?php echo $text_feel; ?></span></a></li>
    <li id="page"><a href="<?php echo $form; ?>"><i class="fa fa-file fa-fw"></i><span><?php echo $text_form; ?></span></a></li>
    <li id="photo"><a href="<?php echo $photo; ?>"><i class="fa fa-picture-o fa-fw"></i><span><?php echo $text_photo; ?></span></a></li>
    <li id="design"><a href="<?php echo $banner; ?>"><i class="fa fa-television fa-fw"></i><span><?php echo $text_banner; ?></span></a></li>
    <li id="contact"><a href="<?php echo $contact1; ?>"><i class="fa fa-envelope fa-fw"></i><span><?php echo $text_contact1; ?></span></a></li>
	
	<li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
		<ul>
			<li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
			<li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>          
        
    </li>      
</ul>
