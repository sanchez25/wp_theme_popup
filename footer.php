<?php if (is_amp()):

    $amp_class = 'content_amp';

endif; ?>

<?php
    $offer_link = get_field('offer_link', 'option');
?>

<footer>
    <div class="footer <?php echo $amp_class; ?>">
        <div class="footer__copyright">
            <div class="footer__copyright-top">
                <span>© <?php echo date('Y');?> Slottica</span>
                <div class="eighteen">18+</div>
            </div>
            <div class="footer__copyright-bottom">
                <img class="bga-img" src="<?php echo get_home_url(); ?>/wp-content/uploads/2024/08/begamble-icon.webp" width="488" height="50" alt="BGA">
                <a href="//www.dmca.com/Protection/Status.aspx?ID=229f78c2-775a-4928-bad4-873553e8fa8f" title="DMCA.com Protection Status" class="dmca-badge"> <img src ="https://images.dmca.com/Badges/DMCA_logo-grn-btn100w.png?ID=229f78c2-775a-4928-bad4-873553e8fa8f"  alt="DMCA.com Protection Status" /></a>  <script src="https://images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script>
            <div>
        </div>
        <?php if (is_amp()):?>
			<div class="scroll-top <?php echo $amp_class; ?>" on="tap:scroll.scrollTo(duration=200)">
                <div class="scroll-top-bg">    
			        <img src="<?php echo get_template_directory_uri() ?>/img/up-icon.svg" alt="scroll to top">
                </div>
            </div>
		<?php else: ?>
			<div class="scroll-top">
                <div class="scroll-top-bg"> 
			        <img src="<?php echo get_template_directory_uri() ?>/img/up-icon.svg" alt="scroll to top">
                </div>
			</div>
		<?php endif; ?>
    </div>
</footer>
<div class="fixed_buttons">
    <?php if (is_amp()): ?>
        <button class="button reg_button" on="tap:AMP.navigateTo(url='<?php echo $offer_link; ?>')" >
    <?php else: ?>
        <button class="button reg_button" onclick="location.href='<?php echo $offer_link; ?>'">
    <?php endif; ?>
        Registro 
    </button>
    <?php if (is_amp()): ?>
        <button class="button log_button" on="tap:AMP.navigateTo(url='<?php echo $offer_link; ?>')" >
    <?php else: ?>
        <button class="button log_button" onclick="location.href='<?php echo $offer_link; ?>'">
    <?php endif; ?>
        Entrar
    </button>
</div>

<?php wp_footer(); ?>

<script src="<?php echo get_template_directory_uri() ?>/js/main.js"></script>
<script src="<?php echo get_template_directory_uri() ?>/js/cookie.js"></script>

</body>
</html>
