<?php
/**
 * The Login Button
 *
 * @package ChoctawNation
 */

if ( is_user_logged_in() ) {
	echo '<a href="' . wp_logout_url( home_url() ) . '" class="btn btn-outline-light">Logout</a>';
} else {
	echo '<a href="' . wp_login_url( home_url() ) . '" class="btn btn-light">Login</a>';
}
