<?php
/***************************************************************************
 *   Copyright (C) 2006 by phpSysInfo - A PHP System Information Script    *
 *   http://phpsysinfo.sourceforge.net/                                    *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

// $Id: indicator.php,v 1.4 2006/06/15 18:42:30 bigmichi1 Exp $

if ( ! defined( 'IN_PHPSYSINFO' ) ) {
	die( "No Hacking" );
}

$start   = $_GET['color1'];
$end     = $_GET['color2'];
$percent = $_GET['percent'];
$height  = $_GET['height'];
  
$width   = 300;

sscanf( $start, "%2x%2x%2x", $rbase, $gbase, $bbase );
sscanf( $end, "%2x%2x%2x", $rend, $gend, $bend );

if( $rbase == $rend ) $rend = $rend - 1;
if( $gbase == $gend ) $gend = $gend - 1;
if( $bbase == $bend ) $bend = $bend - 1;

$rmod = ( $rend - $rbase ) / $width;
$gmod = ( $gend - $gbase ) / $width;
$bmod = ( $bend - $bbase ) / $width;

$image = imagecreatetruecolor( $width, $height );
imagefilledrectangle( $image, 0, 0, $width, $height, imagecolorallocate( $image, 255,255,255 ) );
  
$step = $width / 100;

for( $i = 0; $i < $percent * $step; $i = $i + $step + 1 ) {
	$r = ( $rmod * $i ) + $rbase;
	$g = ( $gmod * $i ) + $gbase;
	$b = ( $bmod * $i ) + $bbase;
	$color = imagecolorallocate( $image, $r, $g, $b );
	imagefilledrectangle( $image, $i, 0, $i + $step, $height, $color );
}

imagerectangle( $image, 0, 0, $width - 1, $height - 1, imagecolorallocate( $image, 0, 0, 0 ) );
imagepng( $image );
imagedestroy( $image );
?>
