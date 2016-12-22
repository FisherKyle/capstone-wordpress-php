<?php

/**
 * Loads the Themeco shared library
 */

class Cornerstone_Tco extends Cornerstone_Plugin_Component {

	public function setup() {
		add_action( 'init', array( $this, 'tco_init' ) );
		add_action( 'admin_init', array( $this, 'tco_init' ) );
	}

	public function tco_init() {

		$tco = cs_tco();

		$tco->init( array(
			'url' => $this->url( 'includes/tco/' )
		) );

		add_filter( 'tco_localize_' . $tco->handle( 'admin-js' ), array( $this, 'localize_admin_js' ) );
		add_filter( 'tco_localize_' . $tco->handle( 'updates' ), array( $this, 'localize_updates' ) );

	}

	public function localize_admin_js() {
		return $this->plugin->config( 'admin/tco-admin' );
	}

	public function localize_updates( $strings ) {

		$strings = array_merge( $strings, array(
			'connection-error' => __( 'Could not establish connection. For assistance, please start by reviewing our article on troubleshooting <a href="https://community.theme.co/kb/connection-issues/">connection issues.</a>', 'cornerstone' )
		) );

		return $strings;

	}

}
