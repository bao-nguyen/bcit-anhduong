<footer>
  <div class="container">
    <div class="row" style="padding: 10px">      
      <div class="col-sm-4 text-center">
      
      <div class="row" style="width:15%; margin-left:auto; margin-right:auto">
            <img class="img-responsive img-center" src="image/map/logo.png"/>
        </div>
        <div class="row" style="">
            <h5><?php echo $store; ?></h5>
        <?php echo $text_address; ?><br />
        <?php echo $text_telephone; ?>
        </div>
        
      </div>
      <div class="col-sm-4 text-center">
        <div class="row" >
            <img class="img-responsive img-center" src="image/map/bando.png"/>
        </div>
      </div>
      <div class="col-sm-4 text-center">
        <div class="row" >
            <div class="fb-page"                 
                data-href="https://www.facebook.com/Trung-tâm-Ngoại-ngữ-Ánh-Dương-Anh-Duong-Language-Center-203133863383707"
                data-width="340" 
                data-hide-cover="false"
                data-show-facepile="true" 
                data-show-posts="false"
                >
                
            </div>
        </div>  
         
      </div>   
    </div>
    <hr>
    <div class="text-center" style="margin-bottom:10px"><?php echo $powered; ?></div>
  </div>
</footer>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.5";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</body>
</html>