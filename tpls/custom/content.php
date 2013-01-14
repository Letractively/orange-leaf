<?php if (IS_DEBUGGING) echo '<!-- '. __FILE__ ." -->\n"; 
else { ?>
<!--Facebook-->
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<!--Facebook-->
		
	<table style="text-align: center;" border="0" cellpadding="5" cellspacing="2" style="width:300px;">
	  <tbody>
	    <tr align="center">
		<td><div style="width:150px">

		<!--Twitter-->
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://the-islander.net" data-text="Read &quot;the Islander&quot;" data-via="IslanderThe">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		<!--Twitter-->
		
			</div>
		</td>
	    <td><div style="width:150px">
	    
		<!-- Put this div tag to the place, where the Like block will be -->
			<div id="vk_like"></div>
			<script type="text/javascript">
			VK.Widgets.Like("vk_like", {type: "button"}, "<? echo $page_id; ?>");
			</script>
		<!-- Put this div tag to the place, where the Like block will be -->
		
		</div>
		</td>
		<td><div style="width:150px">
		
		<!--Facebook-->
			<div class="fb-like" data-href="http://the-islander.net" data-send="false" data-width="450" data-show-faces="false"></div>
		<!--Facebook-->
		
		</div>
		</td>
		</tr>
	  </tbody>
	</table>
<?php } ?>