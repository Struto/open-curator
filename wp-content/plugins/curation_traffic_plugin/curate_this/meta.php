<script type="text/javascript">
var checkbox = document.getElementById("chbx");
function foo(){
  if(checkbox.checked){
    alert("meap");
  };
};
</script>
<div class="my_meta_control">
	<label>Curated Link from: <em style="font-weight: normal;"><?php echo getDomainNameCurationTraffic($meta['curated_link'])."<br>";  ?></em></label>
	<p>
		<input type="text" name="_my_meta[curated_link]" value="<?php if(!empty($meta['curated_link'])) echo $meta['curated_link']; ?>"/>
		<span>This is your original curated URL (default is: "See full story on DOMAIN.com" in the post box.</span>
	</p>
</div>