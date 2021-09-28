<?php

function toggler_1() { 

	$toggle_position_horizontal = insopt( 'toggle-position-horizontal' );
	$toggle_position_vertical = insopt( 'toggle-position-vertical' );
?>
    
    <div <?php if (insopt( 'ins-layout' ) == 3 || insopt( 'ins-layout' ) == 5){ echo 'data-fancybox data-src="#ins-popup" href="javascript:;"';} ?> id="ins-toggle-button" class="ins-toggle-button ins-toggle-button-1 ins-position-<?php echo $toggle_position_horizontal; echo '-'; echo $toggle_position_vertical; ?> compensate-for-scrollbar cartboom">
		
		<?php if( insopt( 'wi-cart-image' ) && insopt( 'wi-icon-choice' ) == true ){ ?>
			<img src="<?php echo insopt( 'wi-cart-image' ); ?>" alt="">
		<?php }else{ ?>
			<?php instantio_svg_icon(insopt( 'cart-icon' )); ?>
		<?php } ?>
		
		<span class="ins-items-count ins-position-right-top"><?php echo instantio_cart_count(); ?></span>
	</div>
    
<?php }

function toggler_2() { 
	$toggle_position_horizontal = insopt( 'toggle-position-horizontal' );
	
?>

	<div <?php if (insopt( 'ins-layout' ) == 3 || insopt( 'ins-layout' ) == 5){ echo 'data-fancybox data-src="#ins-popup" href="javascript:;"';} ?> id="ins-toggle-button" class="ins-toggle-button ins-toggle-button-2 ins-position-<?php echo $toggle_position_horizontal; ?> compensate-for-scrollbar">
		
		<?php if( insopt( 'wi-cart-image' ) && insopt( 'wi-icon-choice' ) == true ){ ?>
			<img src="<?php echo insopt( 'wi-cart-image' ); ?>" alt="">
		<?php }else{ ?>
			<?php instantio_svg_icon(insopt( 'cart-icon' )); ?>
		<?php } ?>
		
		<span class="ins-items-count"><?php echo instantio_cart_count(); ?></span>
	</div>
    
<?php } ?>