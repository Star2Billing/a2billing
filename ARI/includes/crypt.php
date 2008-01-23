<?php

/*
 * Allows encrypt and decrypt
 */
class Crypt {

  /**
   * Gets a random value for encryption
   * - From php.net docs
   *
   * @param $iv_len
   *   length of random variable
   */
  function getRndIV($iv_len) {

    $iv = '';
    while ($iv_len-- > 0) {
      $iv .= chr(mt_rand() & 0xff);
    }
    return $iv;
  }

  /**
   * Encrypts string
   * - From php.net docs
   *
   * @param $str
   *   string to encrypt
   * @param $salt
   *   password to use for encryption
   * @param $iv_len
   *   length of random number
   */
  function encrypt($str, $salt, $iv_len = 16) {

    $str .= "\x13";
    $n = strlen($str);
    if ($n % 16) $str .= str_repeat("\0", 16 - ($n % 16));
    $i = 0;
    $enc_text = $this->getRndIV($iv_len);
    $iv = substr($salt ^ $enc_text, 0, 512);
    while ($i < $n) {
      $block = substr($str, $i, 16) ^ pack('H*', md5($iv));
      $enc_text .= $block;
      $iv = substr($block . $iv, 0, 512) ^ $salt;
      $i += 16;
    }
    return base64_encode($enc_text);
  }

  /**
   * Decrypts string
   * - From php.net docs
   *
   * @param $enc
   *   encrypted string to decrypt
   * @param $salt
   *   password to use for encryption
   * @param $iv_len
   *   length of random number
   */
  function decrypt($enc, $salt, $iv_len = 16) {

     $enc = base64_decode($enc);
     $n = strlen($enc);
     $i = $iv_len;
     $str = '';
     $iv = substr($salt ^ substr($enc, 0, $iv_len), 0, 512);
     while ($i < $n) {
         $block = substr($enc, $i, 16);
         $str .= $block ^ pack('H*', md5($iv));
         $iv = substr($block . $iv, 0, 512) ^ $salt;
         $i += 16;
     }
     return preg_replace('/\\x13\\x00*$/', '', $str);
  }
}


?>