<?php
    $offer_link = get_field('offer_link', 'option');
?>
<div class="banner">
    <div class="banner__content">
        <div class="banner__text">
            <span class="banner__text-first">¡300% + 30 FS!</span>
            <span class="banner__text-third">¡Damos bonificaciones de bienvenida a los tres primeros depósitos!</span>
            <?php if (is_amp()): ?>
            <button class="button content_btn" on="tap:AMP.navigateTo(url='<?php echo $offer_link; ?>')">
                <?php else: ?>
                    <button class="button content_btn" onclick="location.href='<?php echo $offer_link; ?>'">
                <?php endif; ?>
                Obtener
            </button>
        </div>
    </div>
</div>