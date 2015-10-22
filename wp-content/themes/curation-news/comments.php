<?php
	if (post_password_required())
		return;
?>

<div id="comments">
	<?php if (have_comments()) : ?>
<h3 id="comments1"><?php comments_number('No Responses', 'One Response', '% Responses' );?> to &#8220;<?php the_title(); ?>&#8221;</h3>
		<ol class="commentlist">
			<?php 	wp_list_comments(array('callback' => 'curationnews_comment')); ?>
		</ol>

		<?php if (get_comment_pages_count() > 1 &o& get_option('page_comments')) : ?>
		<ul class="pager">
			<li class="previous"><?php previous_comments_link(__( '&laquo; Older Comments', 'curationnews')); ?></li>
			<li class="next"><?php next_comments_link(__('Newer Comments &raquo;', 'curationnews')); ?></li>
		</ul>
		<?php endif;?>

	<?php
	elseif (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
	endif;

 if ( comments_open() ) : ?>
<section id="respond" class="respond-form">

	<h3 id="comment-form-title"><i class="icon-comment"></i> <?php comment_form_title( __("Leave a Reply","curationnews"), __("Leave a Reply to","curationnews") . ' %s' ); ?></h3>

	<div id="cancel-comment-reply">
		<p class="small pull-right">  <?php cancel_comment_reply_link( __("<i class='icon-remove'></i> Cancel","curationnews") ); ?></p>
	</div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
  	<div class="help">
  		<p><?php _e("You must be","curationnews"); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e("logged in","curationnews"); ?></a> <?php _e("to post a comment","curationnews"); ?>.</p>
  	</div>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="form-vertical" id="commentform">
	<?php echo '<input type="hidden" id="wp_unfiltered_html_comment" name="_wp_unfiltered_html_comment" value="' . wp_create_nonce( 'unfiltered-html-comment_' . $post->ID ) . '" />';
    if ( is_user_logged_in() ) : ?>

	<p class="comments-logged-in-as"><?php _e("Logged in as","curationnews"); ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php _e("Log out of this account","curationnews"); ?>"><?php _e("Log out","curationnews"); ?> &raquo;</a></p>

	<?php else : ?>
	
	<ul id="comment-form-elements" class="clearfix">
		
		<li>
			<div class="control-group">
			  <label for="author"><?php _e("Name","curationnews"); ?> <?php if ($req) echo "(required)"; ?></label>
			  <div class="input-prepend">
			  	<span class="add-on"><i class="icon-user"></i></span><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" placeholder="<?php _e("Your Name","curationnews"); ?>" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
			  </div>
		  	</div>
		</li>
		
		<li>
			<div class="control-group">
			  <label for="email"><?php _e("Mail","curationnews"); ?> <?php if ($req) echo "(required)"; ?></label>
			  <div class="input-prepend">
			  	<span class="add-on"><i class="icon-envelope"></i></span><input type="email" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" placeholder="<?php _e("Your Email","curationnews"); ?>" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
			  	<span class="help-inline">(<?php _e("will not be published","curationnews"); ?>)</span>
			  </div>
		  	</div>
		</li>
		
		<li>
			<div class="control-group">
			  <label for="url"><?php _e("Website","curationnews"); ?></label>
			  <div class="input-prepend">
			  <span class="add-on"><i class="icon-home"></i></span><input type="url" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" placeholder="<?php _e("Your Website","curationnews"); ?>" tabindex="3" />
			  </div>
		  	</div>
		</li>
		
	</ul>

	<?php endif; ?>
	
	<div class="clearfix">
		<div class="input"> 
			<textarea name="comment" id="comment" rows="6" placeholder="<?php _e("Your Comment Here...","curationnews"); ?>" tabindex="4"></textarea>
		</div>
	</div>
	
<input class="btn btn-primary" name="submit" type="submit" id="submit" tabindex="5" value="<?php _e("Submit Comment","curationnews"); ?>" />
	  <?php comment_id_fields(); 
	  do_action('comment_form()', $post->ID); 	?>
	</form>
	
	<?php endif; ?>
</section>

<?php endif; ?>
</div>
