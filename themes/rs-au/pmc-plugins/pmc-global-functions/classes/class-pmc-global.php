<?php

final class PMC_Global extends PMC_Singleton {

	private $_body_class_add = array();
	private $_body_class_remove = array();

	protected function _init() {
		// note: this filter need to be added here
		// this function only activate during instatiation when needed at a later state
		add_filter( 'body_class', array( $this, 'filter_body_class' ) );
	}

	/**
	 * implement filter body_class to remove/add body classes
	 * @since 2014-08-05: migrate from bgr_body_class
	 * @param $classes array
	 */
	public function filter_body_class( $classes ) {

		if( !empty( $this->_body_class_add ) ) {
			$classes = array_unique( array_merge( $classes, $this->_body_class_add ) );
		}

		if( !empty( $this->_body_class_remove ) ) {
			$classes = array_diff( $classes, $this->_body_class_remove );
		}

		// Gallery archives need to be treated differently, and CSS :not() isn't reliable
		if( in_array( 'archive', $classes ) && !in_array( 'post-type-archive-gallery', $classes ) ) {
			$classes[] = 'archive-not-gallery';
		}

		return $classes;

	}

	/**
	 * this class will add a class to the body via body_class filter
	 * @since 2014-08-05: migrate from bgr_set_body_class
	 * @param #class array|string
	 */
	public function add_body_class( $class ) {
		if( empty( $class ) ) {
			return;
		}
		if( !is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$this->_body_class_add = array_merge( $this->_body_class_add, $class );
	}

	/**
	 * this function will remove the class from the body via body_class filter
	 * @since 2014-08-05: migrate from bgr_set_body_class
	 * @param #class array|string
	 */
	public function remove_body_class( $class ) {
		if( empty( $class ) ) {
			return;
		}
		if( !is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$this->_body_class_remove = array_merge( $this->_body_class_remove, $class );
	}

}
