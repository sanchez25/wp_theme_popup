<?php
    /* Template name: Main */
?>

<?php get_header(); ?>

<?php if (is_amp()):
	$amp_class = 'content_amp';
endif; ?>
<?php
	$gates_link = get_field('gates_link', 'option');
?>
<div class="content-block <?php echo $amp_class; ?>" itemid="/" itemscope itemtype="https://schema.org/Article">
    <?php
        get_template_part('templates/banner');

        get_template_part('content');
    ?>
</div>
<?php if (is_amp()): ?>
	<amp-state id="popupState">
		<script type="application/json">
			{
				"visible": true
			}
		</script>
	</amp-state>
	<div id="popup-block" class="popup amp" [class]="popupState.visible ? 'popup show' : 'popup'" hidden="true" [hidden]="!popupState.visible">
<?php else: ?>
	<div id="popup-block" class="popup no-amp">
<?php endif; ?>
		<div class="popup__content">
			<?php if (is_amp()): ?>
				<div class="close-popup" on="tap:AMP.setState({popupState: {visible: false}})">
			<?php else: ?>
				<div class="close-popup">
			<?php endif; ?>
				<img src="<?php echo get_template_directory_uri() ?>/img/close.svg" alt="Close popup">
			</div>
			<?php if (is_amp()): ?>
				<button class="button popup-btn" on="tap:AMP.navigateTo(url='<?php echo $gates_link; ?>')" >
			<?php else: ?>
				<button class="button popup-btn" onclick="location.href='<?php echo $gates_link; ?>'">
			<?php endif; ?>
				Juega ahora
			</button>
		</div>
	</div>

<?php get_footer(); ?>