<?php
/**
*
* @package VC
* @version $Id: captcha_gd.php,v 1.19 2007/01/26 16:07:43 acydburn Exp $
* @copyright (c) 2006 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* Based on PHP-Class hn_captcha Version 1.3, released 11-Apr-2006
* Original Author - Horst Nogajski, horst@nogajski.de
*
* @package VC
*/
class captcha
{
    public $width = 240;
    public $height = 60;
    public $captcha_gd_noise = 1;

    public function execute($code, $seed)
    {
        global $config;
        $stats = gd_info();
        $bundled = (substr($stats['GD Version'], 0, 7) === 'bundled') ? true : false;

        preg_match('/[\\d.]+/', $stats['GD Version'], $version);
        $gd_version = (version_compare($version[0], '2.0.1', '>=')) ? 2 : 1;

        // create the image, stay compat with older versions of GD
        if ($gd_version === 2) {
            $func1 = 'imagecreatetruecolor';
            $func2 = 'imagecolorallocate';
        } else {
            $func1 = 'imagecreate';
            $func2 = 'imagecolorclosest';
        }

        $image = $func1($this->width, $this->height);

        if ($bundled) {
            imageantialias($image, true);
        }

        // seed the random generator
        mt_srand($seed);

        // set background color
        $back = imagecolorallocate($image, mt_rand(224, 255), mt_rand(224, 255), mt_rand(224, 255));
        imagefilledrectangle($image, 0, 0, $this->width, $this->height, $back);

        // allocates the 216 websafe color palette to the image
        if ($gd_version === 1) {
            for ($r = 0; $r <= 255; $r += 51) {
                for ($g = 0; $g <= 255; $g += 51) {
                    for ($b = 0; $b <= 255; $b += 51) {
                        imagecolorallocate($image, $r, $g, $b);
                    }
                }
            }
        }

        // fill with noise or grid
        if ($this -> captcha_gd_noise) {
            $chars_allowed = array_merge(range('1', '9'), range('A', 'Z'));
            // random characters in background with random position, angle, color
            for ($i = 0 ; $i < 72; $i++) {
                $size	= mt_rand(8, 23);
                $angle	= mt_rand(0, 360);
                $x		= mt_rand(0, 360);
                #$y		= mt_rand(0, (int) ($this->height - ($size / 5)));
                #Patch from Arheops
                $temp_a = (int) ($size * 1.5);
                $temp_b = (int) ($this->height - ($size / 7));
                if ($temp_a > $temp_b) {
                    $y = mt_rand($temp_b, $temp_a);
                } else {
                    $y = mt_rand($temp_a, $temp_b);
                }
                $color	= $func2($image, mt_rand(160, 224), mt_rand(160, 224), mt_rand(160, 224));
                $text	= $chars_allowed[mt_rand(0, sizeof($chars_allowed) - 1)];
                imagettftext($image, $size, $angle, $x, $y, $color, $this->get_font(), $text);
            }
            unset($chars_allowed);
        } else {
            // generate grid
            for ($i = 0; $i < $this->width; $i += 13) {
                $color	= $func2($image, mt_rand(160, 224), mt_rand(160, 224), mt_rand(160, 224));
                imageline($image, $i, 0, $i, $this->height, $color);
            }

            for ($i = 0; $i < $this->height; $i += 11) {
                $color	= $func2($image, mt_rand(160, 224), mt_rand(160, 224), mt_rand(160, 224));
                imageline($image, 0, $i, $this->width, $i, $color);
            }
        }

        $len = strlen($code);

        for ($i = 0, $x = mt_rand(20, 40); $i < $len; $i++) {
            $text	= strtoupper($code[$i]);
            //echo "text=$text ; code=$code -".strlen($code); exit;
            $angle	= mt_rand(-30, 30);

            $size	= mt_rand(20, 40);
            $y		= mt_rand((int) ($size * 1.5), (int) ($this->height - ($size / 7)));

            $color	= $func2($image, mt_rand(0, 127), mt_rand(0, 127), mt_rand(0, 127));
            $shadow = $func2($image, mt_rand(127, 254), mt_rand(127, 254), mt_rand(127, 254));
            $font = $this->get_font();

            imagettftext($image, $size, $angle, $x + (int) ($size / 15), $y, $shadow, $font, $text);
            imagettftext($image, $size, $angle, $x, $y - (int) ($size / 15), $color, $font, $text);

            $x += $size + 4;
        }

        // Output image
        header('Content-Type: image/png');
        header('Cache-control: no-cache, no-store');
        imagepng($image);
        imagedestroy($image);
    }

    public function get_font()
    {
        static $fonts = array();

        if (!sizeof($fonts)) {
            global $phpbb_root_path;
            $dr = @opendir(dirname(__FILE__).'/fonts/');

            if (!$dr) {
                trigger_error('Unable to open fonts directory.', E_USER_ERROR);
            }

            while (false !== ($entry = readdir($dr))) {
                if (strtolower(pathinfo($entry, PATHINFO_EXTENSION)) == 'ttf') {
                    $fonts[] = dirname(__FILE__).'/fonts/' . $entry;
                }
            }
            closedir($dr);
        }

        return $fonts[mt_rand(0, sizeof($fonts) - 1)];
    }
}
