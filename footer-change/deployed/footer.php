<!-- =====footer section start===== -->
<?php 
   	$contact_details	=	get_field('contact_details', 'option');
   	$phone_number		=	$contact_details['phone_number'];
   	$email_address		=	$contact_details['email_address'];
   	$facebook_link		=	$contact_details['facebook_link'];
	$instagram_link		=	$contact_details['instagram_link'];
    $youtube_link		=	$contact_details['youtube_link'];
	$whatsapp_link 		= 	$contact_details['whatsapp_link'];
	$whatsapp_number 		= 	$contact_details['whatsapp_number'];	
?>
    <footer style="background: url('<?php echo get_template_directory_uri(); ?>/assets/images/footer-bg.jpg' ) no-repeat; background-size: cover; background-position: top;">
        <div class="container">
            <div class="main-block">
                <h3 class="title-light-1"><?php echo get_field('footer_title', 'option'); ?></h3>
                <div class="btn-group">


                    <?php
                        $cta_buttons = get_field('cta_buttons', 'option');

                        if ($cta_buttons):
                            foreach ($cta_buttons as $index => $cta_button):

                                $button = $cta_button['button'];
                                if (!$button) continue;

                                $url   = $button['url'];
                                $title = $button['title'];

                                // odd / even class
                                $btn_class = ($index % 2 === 0) ? 'primary-btn' : 'primary-btn-3';
                        ?>
                            <a <?php echo srDev_link_validation($url); ?> class="<?php echo esc_attr($btn_class); ?>">
                                <?php echo $index == 2 ? 'EMAIL US' : esc_html($title); ?>
                                <svg class="srdev-icon">
                                    <use href="<?php echo get_template_directory_uri(); ?>/assets/icons.svg#fancy-right-arrow-icom"></use>
                                </svg>
                            </a>
                        <?php
                            endforeach;
                        endif;
                    ?>
                </div>
                <div class="bottom-block">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="box">
                                <h3>OMG Group Services</h3>

                                <?php
                                  $defaults = array(
                                      'theme_location'  => 'footer',
                                      'menu'            => '',
                                      'container'       => 'ul',
                                      'container_class' => '',
                                      'container_id'    => '',
                                      'menu_class'      => '',
                                      'menu_id'         => '',
                                      'echo'            => true,
                                      'fallback_cb'     => 'wp_page_menu',
                                      'before'          => '',
                                      'after'           => '',
                                      'link_before'     => '',
                                      'link_after'      => '',
                                      'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                      'depth'           => 0,
                                      'walker'          => ''
                                  );
                                  wp_nav_menu( $defaults );
                                ?>

                                <?php
                                  $defaults = array(
                                      'theme_location'  => 'footer-other',
                                      'menu'            => '',
                                      'container'       => 'ul',
                                      'container_class' => '',
                                      'container_id'    => '',
                                      'menu_class'      => '',
                                      'menu_id'         => '',
                                      'echo'            => true,
                                      'fallback_cb'     => 'wp_page_menu',
                                      'before'          => '',
                                      'after'           => '',
                                      'link_before'     => '',
                                      'link_after'      => '',
                                      'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                      'depth'           => 0,
                                      'walker'          => ''
                                  );
                                  wp_nav_menu( $defaults );
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="box">
                                <h3> OMG! Let’s Get Social</h3>
                                <ul class="social-media">
    <li><a href="<?php echo $facebook_link; ?>" title="Facebook" target="_blank">
        <div class="wrapper">
            <div class="icon">
                <svg class="srdev-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/icons.svg#facebook-icon"></use></svg>
            </div>
            <p>Facebook</p>
        </div>
        </a> 
    </li>
    <li><a href="<?php echo $instagram_link; ?>" title="Instagram" target="_blank">
        <div class="wrapper">
            <div class="icon">
                <svg class="srdev-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/icons.svg#instagram-icon"></use></svg>
            </div>
            <p>Instagram</p>
        </div>
        </a> 
    </li>
    <li><a href="<?php echo $youtube_link; ?>" title="Youtube" target="_blank">
        <div class="wrapper">
            <div class="icon">
                <svg class="srdev-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/icons.svg#youtube-icon"></use></svg>
            </div>
            <p>YouTube</p>
        </div>
        </a> 
    </li>
</ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="box-contact">
                                <h3>Contact Us</h3>
                                <ul>
									<li><a href="<?php echo get_home_url();?>">OMG Entertainment Group (HQ)</a></li>
                                    <li><a href="tel:<?php echo $phone_number; ?>"><strong><?php echo $phone_number; ?> (OMG)</strong></a></li>
                                    <li><a href="mailto:<?php echo $email_address; ?>"><?php echo $email_address; ?></a></li>
									<li class="whatsapp-link-footer">
										<a href="<?php echo $whatsapp_link; ?>" title="WhatsApp" target="_blank" class="d-flex align-items-center gap-1">
											<svg class="srdev-icon" style="fill: #F4D6A0;"><use href="<?php echo get_template_directory_uri(); ?>/assets/icons.svg#whatsapp-omg"></use></svg>
											<?php if( $whatsapp_number ): ?>
												<strong><?php echo $whatsapp_number; ?></strong>
											<?php endif; ?>
										</a>
									</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <div class="box footer-logo-box">
                                <strong class="footer-logo-cta">Visit Entertainment</strong>
                                <?php // Current custom logo kept DORMANT (still set in Customizer, not deleted): the_custom_logo(); ?>
                                <a href="https://creativus-sites.com/demo/dev/omg-entertainment-new" class="custom-logo-link" target="_blank" rel="noopener">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/omg-logo-footer.png" class="custom-logo" alt="OMG Entertainment Group" width="1806" height="787" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright">
                    &copy; <?php echo Date('Y'); ?> OMG Entertainment Group
                </div>
            </div>
        </div>
    </footer><!-- =====footer section end===== -->



          

   

    <a id="back-to-top-button">
        <div>
            <svg class="srdev-icon"><use href="<?php echo get_template_directory_uri(); ?>/assets/icons.svg#fancy-right-arrow-icom"></use></svg>
            <span>back to<br>top</span>
        </div>
    </a>






<script>
(function () {

  function initStickyOnScroll() {
    const header = document.getElementById("siteHeader");
    if (!header) return;

    const triggerPoint = header.offsetTop;

    function onScroll() {
      if (window.scrollY > triggerPoint) {
        header.classList.add("is-sticky");
      } else {
        header.classList.remove("is-sticky");
      }
    }

    window.removeEventListener("scroll", onScroll);
    window.addEventListener("scroll", onScroll);
  }

  // normal load
  document.addEventListener("DOMContentLoaded", initStickyOnScroll);

  // barba page change support
  document.addEventListener("barba:after", initStickyOnScroll);

})();
</script>


<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
  const input = document.querySelector('#input_1_25'); // formID_fieldID
  if (!input) return;

  const prefix = '+614';

  // Set initial value
  if (!input.value) {
    input.value = prefix + ' ';
  }

  // Prevent deleting prefix
  input.addEventListener('keydown', function (e) {
    if (
      this.selectionStart <= prefix.length + 1 &&
      (e.key === 'Backspace' || e.key === 'Delete')
    ) {
      e.preventDefault();
    }
  });

  input.addEventListener('input', function () {
    // Remove everything except digits
    let digits = this.value.replace(/\D/g, '');

    // Remove leading 614 if user pasted full number
    if (digits.startsWith('614')) {
      digits = digits.slice(3);
    }

    // Limit to 8 digits
    digits = digits.slice(0, 8);

    // Format +614 XXXX XXXX
    let formatted = prefix + ' ';
    if (digits.length > 0) {
      formatted += digits.substring(0, 4);
    }
    if (digits.length > 4) {
      formatted += ' ' + digits.substring(4);
    }

    this.value = formatted;
  });
});
</script> -->


<script>
window.addEventListener('load', function () {
  const loader = document.getElementById('loader');
  if (!loader) return;

  loader.style.transition = 'opacity .5s ease';
  loader.style.opacity = '0';

  setTimeout(function () {
    loader.style.display = 'none';
  }, 500);
});
</script>


<!-- time flatpickr js here -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function () {

    function initFlatpickr() {

        if (typeof window.flatpickr === 'undefined') return;

        document.querySelectorAll('.gf-flatpickr-time input').forEach(function (input) {

            if (input._flatpickr) return;

            flatpickr(input, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                time_24hr: false,
                minuteIncrement: 30,
                defaultHour: 9,
                defaultMinute: 0,
                allowInput: false,
                disableMobile: true
            });
        });
    }

    // 1️⃣ Normal page load
    document.addEventListener('DOMContentLoaded', initFlatpickr);

    // 2️⃣ Gravity Forms AJAX render
    document.addEventListener('gform_post_render', initFlatpickr);

    // 3️⃣ Fallback – when user focuses field
    document.addEventListener('focusin', function (e) {
        if (e.target.closest('.gf-flatpickr-time input')) {
            initFlatpickr();
        }
    });

})();
</script>























<?php wp_footer(); ?>
</body>
</html>