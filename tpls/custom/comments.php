<?php if (IS_DEBUGGING) echo '<!-- '. __FILE__ ." -->\n"; 
else { ?>
<!--TABS-->
<dl class="tabs">
<dt class="selected">ВКонтакте</dt>
<dd class="selected">
<div class="tab-content">
  
<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, width: "496", attach: "*"}, "<?php echo $page_id; ?>");
</script>
<!-- Put this div tag to the place, where the Comments block will be -->  

</div>
</dd>
<dt>FaceBook</dt>
<dd>
<div class="tab-content">

		<!--Facebook-->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=320808928026866";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	[10/23/12 3:38:53 PM] Pasha Sen'ko: <div class="fb-comments" data-href="http://the-islander.net/" data-num-posts="5" data-width="496"></div>  
		<!--Facebook-->	

</div>
</dd>
</dl>

<!--TABS-->
<?php } ?>