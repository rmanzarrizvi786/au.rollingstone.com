<?php

/**
 * This class implement basic data encryption using mcrypt
 */

class PMC_Crypto extends PMC_Singleton {
	const DEFAULT_ENCRYPTION_KEY = '0f9ca14170b13b1599d9629d073787d5';
	protected $_iv_size = 16;

	protected function _init() {
		$this->_iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
	}

	/**
	 * @param string $plaintext The text to be encrypt
	 * @param string $key Optional secret string
	 * @return string The encrypted text
	 */
	public function encrypt( $plaintext, $key = false ) {
		$key = !empty( $key ) ? md5( $key ) : md5( self::DEFAULT_ENCRYPTION_KEY );
		$iv = mcrypt_create_iv( $this->_iv_size, MCRYPT_RAND );
		$ciphertext  = mcrypt_encrypt( MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv );
		return '[' . base64_encode( $iv . $ciphertext ) .']';
	}

	/**
	 * @param string $ciphertext The encrypted text
	 * @param string $key Optional secret string
	 * @return string Plain text
	 */
	public function decrypt( $ciphertext, $key = false ) {
		if ( substr( $ciphertext, 0, 1 ) != '[' || substr( $ciphertext, -1 ) != ']' ) {
			return $ciphertext;
		}
		$key = !empty( $key ) ? md5( $key ) : md5( self::DEFAULT_ENCRYPTION_KEY );
		$ciphertext = substr( $ciphertext, 1, -1 );
		$ciphertext = base64_decode( $ciphertext );
		$iv = substr( $ciphertext, 0, $this->_iv_size );
		$ciphertext = substr( $ciphertext, $this->_iv_size );
		$plaintext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext, MCRYPT_MODE_CBC, $iv );
		return rtrim( $plaintext, "\0" );
	}
}