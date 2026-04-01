<!DOCTYPE html>
<html lang="es-CL">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name='viewport' content='width=device-width,initial-scale=1'/>
    <?php wp_head(); ?>
    <title><?php wp_title(); ?></title>
	<meta name="yandex-verification" content="3a4ebd5a51455286" />
</head>
<body>
    <?php if ( is_amp() ):?>
        <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
		<script async custom-element="amp-bind" src="https://cdn.ampproject.org/v0/amp-bind-0.1.js"></script>
        <amp-sidebar id="sidebar" class="sample-sidebar" layout="nodisplay" side="right">
            <div class="close">
                <button on="tap:sidebar.toggle" class="closeButton">
                    <img class="close__img" src="<?php echo get_template_directory_uri() ?>/img/close.svg" alt="close">
                </button>
            </div>
            <div class="menu_amp">
                <?php 
					if (has_nav_menu('main')) {
						wp_nav_menu( array(
							'menu_class'=>'menu',
							'theme_location'=>'main'
						));
					} else {
						echo '';
					}
				?>
            </div>
        </amp-sidebar>
    <?php endif; ?>
    <div id="scroll"></div>
    <header>
        <?php if ( !is_amp() ):?>
            <div class="header header_desktop">
                <div class="overlay"></div>
                <div class="header__top">
                    <a class="logo" href="/">
                        <img src="<?php echo get_home_url(); ?>/wp-content/uploads/2024/08/logo.svg" width="120" height="45" alt="1win Logo">
                    </a>
                    <div class="main_menu">
                        <?php 
                            if (has_nav_menu('main')){
                                wp_nav_menu( array(
                                    'menu_class'=>'menu',
                                    'theme_location'=>'main'
                                ));
                            } else {
                                echo '';
                            }
                        ?>
                    </div>
                    <div class="header__right">
                        <?php
                            $offer_link = get_field('offer_link', 'option');
                        ?>
                        <div class="buttons">
                            <?php if (is_amp()): ?>
                                <button class="button reg_button" on="tap:AMP.navigateTo(url='<?php echo $offer_link; ?>')" >
                            <?php else: ?>
                                <button  class="button reg_button"  onclick="location.href='<?php echo $offer_link; ?>'">
                            <?php endif; ?>
                                Registro 
                            </button>
                            <?php if (is_amp()): ?>
                                <button class="button log_button" on="tap:AMP.navigateTo(url='<?php echo $offer_link; ?>')" >
                            <?php else: ?>
                                <button  class="button log_button"  onclick="location.href='<?php echo $offer_link; ?>'">
                            <?php endif; ?>
                                Entrar
                            </button>
                        </div>
						<?php 
							if (has_nav_menu('main')) { ?>
								<div class="burger">
									<img src="<?php echo get_template_directory_uri() ?>/img/burger.svg" alt="Burger icon">
								</div>
						<?php } ?>
                        <div class="menu_mobile">
                            <div class="close">
                                <img class="close__img" src="<?php echo get_template_directory_uri() ?>/img/close.svg" alt="Close icon">
                            </div>
                            <?php 
								if (has_nav_menu('main')){
									wp_nav_menu( array(
										'menu_class'=>'menu',
										'theme_location'=>'main'
									));
								} else {
									echo '';
								}
							?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="header content_amp">
                <a class="logo" href="/">
                   <img src="<?php echo get_home_url(); ?>/wp-content/uploads/2024/08/logo.svg" width="120" height="45" alt="1win Logo">
                </a>
				<?php 
					if (has_nav_menu('main')) { ?>
						<button on="tap:sidebar.toggle" class="burger">
							<img src="<?php echo get_template_directory_uri() ?>/img/burger.svg" alt="Burger icon">
						</button>
				<?php } ?>
            </div>
        <?php endif; ?>
    </header>
