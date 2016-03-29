<footer>
  <div class="container">
    <div class="row" style="padding: 10px">      
      <div class="col-sm-12 text-center">                  
        <div class="row" style="">
            <h5><?php echo $store; ?></h5>
        <?php echo $text_address; ?><br />
        <?php echo $text_telephone; ?>
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