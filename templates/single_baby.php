<?php get_header() ?>

	<div id="content">
		<div class="padder">

			<?php do_action( 'bp_before_blog_single_post' ) ?>

			<div class="page" id="blog-single">
					
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
						<style type="text/css">
						.baby_col1{width:35%; float:left;}
						.baby_col2{width:60%; float:left; color:#000000}
						
						<?php if (get_post_meta($post->ID, '_baby_babygender', true) == 'male') {  ?>
						.baby_col1 img{border:8px solid #92E0FC;}
						<?php } else{?>
						.baby_col1 img{border:8px solid #F8B1BE;}
						<?php } ?>
						
						.baby_details h1{font-size:26px; line-height:2; color:#666666;}
						.baby_details h5{color:#999999;}
						.baby_table{width:90%;}
						.baby_table td{padding:8px 5px 8px 5px }
						.baby_table th{padding:8px 5px 8px 5px }
						.parent_baby{background-color:#333333; padding:5px 5px 5px 5px; color:#FFFFFF; font-size:16px;}
						.comment_section{margin-top:10px; color:#e0c398;}
						.line_border{border-bottom:1px solid #ccc; margin-top:20px; margin-bottom:10px;}
						.quote{ color:#572700;}
						.comments-title{color:#572700;}
						.comment-content{color:#572700;}
						.custom_comments{color:#572700;}
						.custom_comments span{color:#333333;}
						</style>
						
						<div class="baby_details">	
							
			<div class="baby_col1">
			<?php  $plugins_url = plugins_url()."/newborn_babies"; ?>
			
			<?php echo get_the_post_thumbnail($post->ID); ?>
			
			
			
			</div>
			
			<div class="baby_col2">
			<h5>WELCOMING</h5>
			<h1><?php echo get_the_title(); ?></h1>
			<table class="baby_table">
			<tr>
          	<td><p><strong>Gender</strong></p></td>
            <td><p class="babyDetails"><?php echo ucfirst(get_post_meta($post->ID, '_baby_babygender', true)); ?></p></td>
         	</tr>
			<tr>
          	<td><p><strong>Day of Birth</strong></p></td>
            <td><p class="babyDetails">	<?php echo $birth_date = get_post_meta($post->ID, '_baby_birthmonth', true).'/'.get_post_meta($post->ID, '_baby_birthday', true).'/'.get_post_meta($post->ID, '_baby_birthyear', true); ?>
</p></td>
          </tr>
          <tr>
          	<td><p><strong>Time of Birth</strong></p></td>
            <td><p class="babyDetails"><?php echo get_post_meta($post->ID, '_baby_time', true); ?></p></td>
          </tr>
          <tr>
          	<td><p><strong>Weight/Length</strong></p></td>
            <td><p class="babyDetails"><?php echo get_post_meta($post->ID, '_baby_weight', true); ?></p></td>
          </tr>
          <tr>
          	<td><p><strong>Delivered By</strong></p></td>
            <td><p class="babyDetails"><?php echo get_post_meta($post->ID, '_baby_delivered', true); ?></p></td>
          </tr>
          <tr>
          	<td><p><strong>Mother's Doctor</strong></p></td>
            <td><p class="babyDetails"><?php echo get_post_meta($post->ID, '_baby_mother', true); ?></p></td>
          </tr>
		  <tr>
          	<td><p><strong>Baby's Doctor</strong></p></td>
            <td><p class="babyDetails"><?php echo get_post_meta($post->ID, '_baby_babydoctor', true); ?></p></td>
          </tr>
        
		
			</table>
			<br>
			<div class="parent_baby">
			Parents
			</div> <br>
			<h3><?php echo get_post_meta($post->ID, '_baby_parents', true); ?></h3>
			<br><br>
			<div class="quote">
			<img src="<?php echo $plugins_url; ?>/images/quote_open.png">
			<?php echo get_post_meta($post->ID, '_baby_babycomments', true); ?>
			<img src="<?php echo $plugins_url; ?>/images/quote_close.png">
			</div>
			

			<br>
			</div>
			
			<div style="clear:both;"></div>
			<div class="line_border"></div>
							
							<?php if( $cap->single_post_hide_comment_info != 'hide') { ?>
								<p class="comment_section"><span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'x2' ), __( '1 Comment &#187;', 'x2' ), __( '% Comments &#187;', 'x2' ) ); ?></span></p>
							<?php } ?>
									
						</div>
					</div>
	
					
	
					<?php //comments_template(); ?>
					<?php 
					$args_comments = array(
        'post_id'=>$post->ID,
        'status'=>'approve',
    );
					?>
					<?php foreach (get_comments($args_comments) as $comment): ?>
					<div class="custom_comments"><span><?php echo ucfirst($comment->comment_author); ?></span> Said: <em>"<?php echo $comment->comment_content; ?>"</em> @ <?php echo mysql2date('m/d/Y', $comment->comment_date) ; ?> .</div> <br>
					<?php endforeach; ?>
		
		<?php
		ob_start();
comment_form($args);
$comment_form = ob_get_clean();
//insert code to modify $comment_form
echo $comment_form;
		?>
					<?php endwhile; else: ?>
						<p><?php _e( 'Sorry, no posts matched your criteria.', 'x2' ) ?></p>
					<?php endif; ?>
					
					<?php do_action( 'x2_list_posts_on_post' ) ?>
			</div>

			<?php do_action( 'bp_after_blog_single_post' ) ?>
			
<?php edit_post_link( __( 'Edit this entry.', 'x2' ), '<br><p><br>', '</p>'); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer() ?>