<?php
/*=======================================================================
// File: 	JPGRAPH_LOG.PHP
// Description:	Log scale plot extension for JpGraph
// Created: 	2001-01-08
// Author:	Johan Persson (johanp@aditus.nu)
// Ver:		$Id: jpgraph_log.php,v 1.15.2.6 2004/06/12 21:41:37 aditus Exp $
//
// License:	This code is released under QPL
// Copyright (C) 2001,2002 Johan Persson
//========================================================================
*/

DEFINE('LOGLABELS_PLAIN',0);
DEFINE('LOGLABELS_MAGNITUDE',1);

//===================================================
// CLASS LogScale
// Description: Logarithmic scale between world and screen
//===================================================
class LogScale extends LinearScale
{
//---------------
// CONSTRUCTOR

    // Log scale is specified using the log of min and max
    public function LogScale($min,$max,$type="y")
    {
    $this->LinearScale($min,$max,$type);
    $this->ticks = new LogTicks();
    $this->name = 'log';
    }

//----------------
// PUBLIC METHODS

    // Translate between world and screen
    public function Translate($a)
    {
    if ( !is_numeric($a) ) {
        if( $a != '' && $a != '-' && $a != 'x' )
        JpGraphError::Raise('Your data contains non-numeric values.');

        return 1;
    }
    if ($a < 0) {
        JpGraphError::Raise("Negative data values can not be used in a log scale.");
        exit(1);
    }
    if( $a==0 ) $a=1;
    $a=log10($a);

    return ceil($this->off + ($a*1.0 - $this->scale[0]) * $this->scale_factor);
    }

    // Relative translate (don't include offset) usefull when we just want
    // to know the relative position (in pixels) on the axis
    public function RelTranslate($a)
    {
    if ( !is_numeric($a) ) {
        if( $a != '' && $a != '-' && $a != 'x' )
        JpGraphError::Raise('Your data contains non-numeric values.');

        return 1;
    }
    if( $a==0 ) $a=1;
    $a=log10($a);

    return round(($a*1.0 - $this->scale[0]) * $this->scale_factor);
    }

    // Use bcpow() for increased precision
    public function GetMinVal()
    {
    if( function_exists("bcpow") )

        return round(bcpow(10,$this->scale[0],15),14);
    else
        return round(pow(10,$this->scale[0]),14);
    }

    public function GetMaxVal()
    {
    if( function_exists("bcpow") )

        return round(bcpow(10,$this->scale[1],15),14);
    else
        return round(pow(10,$this->scale[1]),14);
    }

    // Logarithmic autoscaling is much simplier since we just
    // set the min and max to logs of the min and max values.
    // Note that for log autoscale the "maxstep" the fourth argument
    // isn't used. This is just included to give the method the same
    // signature as the linear counterpart.
    public function AutoScale(&$img,$min,$max,$dummy)
    {
    if( $min==0 ) $min=1;

    if ($max <= 0) {
        JpGraphError::Raise('Scale error for logarithmic scale. You have a problem with your data values. The max value must be greater than 0. It is mathematically impossible to have 0 in a logarithmic scale.');
    }
    $smin = floor(log10($min));
    $smax = ceil(log10($max));
    $this->Update($img,$smin,$smax);
    }
//---------------
// PRIVATE METHODS
} // Class

//===================================================
// CLASS LogTicks
// Description:
//===================================================
class LogTicks extends Ticks
{
    public $label_logtype=LOGLABELS_MAGNITUDE;
//---------------
// CONSTRUCTOR
    public function LogTicks()
    {
    }
//---------------
// PUBLIC METHODS
    public function IsSpecified()
    {
    return true;
    }

    public function SetLabelLogType($aType)
    {
    $this->label_logtype = $aType;
    }

    // For log scale it's meaningless to speak about a major step
    // We just return -1 to make the framework happy (specifically
    // StrokeLabels() )
    public function GetMajor()
    {
    return -1;
    }

    public function SetTextLabelStart($aStart)
    {
    JpGraphError::Raise('Specifying tick interval for a logarithmic scale is undefined. Remove any calls to SetTextLabelStart() or SetTextTickInterval() on the logarithmic scale.');
    }

    public function SetXLabelOffset($dummy)
    {
    // For log scales we dont care about XLabel offset
    }

    // Draw ticks on image "img" using scale "scale". The axis absolute
    // position in the image is specified in pos, i.e. for an x-axis
    // it specifies the absolute y-coord and for Y-ticks it specified the
    // absolute x-position.
    public function Stroke(&$img,&$scale,$pos)
    {
    $start = $scale->GetMinVal();
    $limit = $scale->GetMaxVal();
    $nextMajor = 10*$start;
    $step = $nextMajor / 10.0;


    $img->SetLineWeight($this->weight);

    if ($scale->type == "y") {
        // member direction specified if the ticks should be on
        // left or right side.
        $a=$pos + $this->direction*$this->GetMinTickAbsSize();
        $a2=$pos + $this->direction*$this->GetMajTickAbsSize();

        $count=1;
        $this->maj_ticks_pos[0]=$scale->Translate($start);
        $this->maj_ticklabels_pos[0]=$scale->Translate($start);
        if( $this->supress_first )
        $this->maj_ticks_label[0]="";
        else {
        if ($this->label_formfunc != '') {
            $f = $this->label_formfunc;
            $this->maj_ticks_label[0]=call_user_func($f,$start);
        } elseif( $this->label_logtype == LOGLABELS_PLAIN )
            $this->maj_ticks_label[0]=$start;
        else
            $this->maj_ticks_label[0]='10^'.round(log10($start));
        }
        $i=1;
        for ($y=$start; $y<=$limit; $y+=$step,++$count) {
        $ys=$scale->Translate($y);
        $this->ticks_pos[]=$ys;
        $this->ticklabels_pos[]=$ys;
        if ($count % 10 == 0) {
            if ($this->majcolor!="") {
            $img->PushColor($this->majcolor);
            $img->Line($pos,$ys,$a2,$ys);
            $img->PopColor();
            } else
            $img->Line($pos,$ys,$a2,$ys);

            $this->maj_ticks_pos[$i]=$ys;
            $this->maj_ticklabels_pos[$i]=$ys;

            if ($this->label_formfunc != '') {
            $f = $this->label_formfunc;
            $this->maj_ticks_label[$i]=call_user_func($f,$nextMajor);
            } elseif( $this->label_logtype == 0 )
            $this->maj_ticks_label[$i]=$nextMajor;
            else
            $this->maj_ticks_label[$i]='10^'.round(log10($nextMajor));
            ++$i;
            $nextMajor *= 10;
            $step *= 10;
            $count=1;
        } else {
            if( $this->mincolor!="" ) $img->PushColor($this->mincolor);
            $img->Line($pos,$ys,$a,$ys);
            if( $this->mincolor!="" ) $img->PopCOlor();
        }
        }
    } else {
        $a=$pos - $this->direction*$this->GetMinTickAbsSize();
        $a2=$pos - $this->direction*$this->GetMajTickAbsSize();
        $count=1;
        $this->maj_ticks_pos[0]=$scale->Translate($start);
        $this->maj_ticklabels_pos[0]=$scale->Translate($start);
        if( $this->supress_first )
        $this->maj_ticks_label[0]="";
        else {
        if ($this->label_formfunc != '') {
            $f = $this->label_formfunc;
            $this->maj_ticks_label[0]=call_user_func($f,$start);
        } elseif( $this->label_logtype == 0 )
            $this->maj_ticks_label[0]=$start;
        else
            $this->maj_ticks_label[0]='10^'.round(log10($start));
        }
        $i=1;
        for ($x=$start; $x<=$limit; $x+=$step,++$count) {
        $xs=$scale->Translate($x);
        $this->ticks_pos[]=$xs;
        $this->ticklabels_pos[]=$xs;
        if ($count % 10 == 0) {
            $img->Line($xs,$pos,$xs,$a2);
            $this->maj_ticks_pos[$i]=$xs;
            $this->maj_ticklabels_pos[$i]=$xs;

            if ($this->label_formfunc != '') {
            $f = $this->label_formfunc;
            $this->maj_ticks_label[$i]=call_user_func($f,$nextMajor);
            } elseif( $this->label_logtype == 0 )
            $this->maj_ticks_label[$i]=$nextMajor;
            else
            $this->maj_ticks_label[$i]='10^'.round(log10($nextMajor));
            ++$i;
            $nextMajor *= 10;
            $step *= 10;
            $count=1;
        } else
            $img->Line($xs,$pos,$xs,$a);
        }
    }

    return true;
    }
} // Class
/* EOF */
