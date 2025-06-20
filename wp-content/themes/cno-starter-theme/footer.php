<?php
/**
 * Basic Footer Template
 *
 * @package ChoctawNation
 */

?>

<footer class="docs-footer container-xxl border-top border-1 mt-5 py-2">
	<div class="row gx-0">
		<div class="col d-flex flex-wrap gap-2">
			<p class="fst-italic fs-6 mb-0">See something inaccurate?</p><button data-bs-toggle="modal" data-bs-target="#contact-modal"
					class="d-inline-block nav-link btn-link  text-decoration-underline fst-italic fs-6">Let us
				know!</button>
		</div>
	</div>
</footer>
<div class="modal fade" id="contact-modal" tabindex="-1" aria-labelledby="contact-modal-label">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Contact Us</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php echo do_shortcode( '[gravityform id="1" title="false" ajax="true"]' ); ?>
			</div>
			<div class="modal-footer justify-content-start">
				<p class="fs-base fst-italic">This site is protected by reCAPTCHA and the Google
					<a target="_blank" href="https://policies.google.com/privacy">Privacy Policy</a> and
					<a target="_blank" href="https://policies.google.com/terms">Terms of Service</a> apply.
				</p>
			</div>
		</div>
	</div>
</div>
<?php get_template_part( 'template-parts/modal', 'site-search' ); ?>
<?php wp_footer(); ?>
</body>

</html>
