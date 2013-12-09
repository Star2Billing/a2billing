<?php
/*=======================================================================
// File:	JPGRAPH_RADAR.PHP
// Description: Radar plot extension for JpGraph
// Created: 	2001-02-04
// Author:	Johan Persson (johanp@aditus.nu)
// Ver:		$Id: jpgraph_radar.php,v 1.7.2.5 2004/06/12 17:46:01 aditus Exp $
//
// License:	This code is released under QPL
// Copyright (C) 2001,2002 Johan Persson
//========================================================================
*/

require_once 'jpgraph_plotmark.inc';

class RadarLogTicks extends Ticks
{
//---------------
// CONSTRUCTOR
    public function RadarLogTicks()
    {
    }
//---------------
// PUBLIC METHODS

    // TODO: Add Argument grid
    public function Stroke(&$aImg,&$grid,$aPos,$aAxisAngle,&$aScale,&$aMajPos,&$aMajLabel)
    {
    $start = $aScale->GetMinVal();
    $limit = $aScale->GetMaxVal();
    $nextMajor = 10*$start;
    $step = $nextMajor / 10.0;
    $count=1;

    $ticklen_maj=5;
    $dx_maj=round(sin($aAxisAngle)*$ticklen_maj);
    $dy_maj=round(cos($aAxisAngle)*$ticklen_maj);
    $ticklen_min=3;
    $dx_min=round(sin($aAxisAngle)*$ticklen_min);
    $dy_min=round(cos($aAxisAngle)*$ticklen_min);

    $aMajPos=array();
    $aMajLabel=array();

    if( $this->supress_first )
        $aMajLabel[]="";
    else
        $aMajLabel[]=$start;
    $yr=$aScale->RelTranslate($start);
    $xt=round($yr*cos($aAxisAngle))+$aScale->scale_abs[0];
    $yt=$aPos-round($yr*sin($aAxisAngle));
    $aMajPos[]=$xt+2*$dx_maj;
    $aMajPos[]=$yt-$aImg->GetFontheight()/2;
    $grid[]=$xt;
    $grid[]=$yt;

    $aImg->SetLineWeight($this->weight);

    for ($y=$start; $y<=$limit; $y+=$step,++$count) {
        $yr=$aScale->RelTranslate($y);
        $xt=round($yr*cos($aAxisAngle))+$aScale->scale_abs[0];
        $yt=$aPos-round($yr*sin($aAxisAngle));
        if ($count % 10 == 0) {
        $grid[]=$xt;
        $grid[]=$yt;
        $aMajPos[]=$xt+2*$dx_maj;
        $aMajPos[]=$yt-$aImg->GetFontheight()/2;
        if (!$this->supress_tickmarks) {
            if( $this->majcolor!="" ) $aImg->PushColor($this->majcolor);
            $aImg->Line($xt+$dx_maj,$yt+$dy_maj,$xt-$dx_maj,$yt-$dy_maj);
            if( $this->majcolor!="" ) $aImg->PopColor();
        }
        $aMajLabel[]=$nextMajor;
        $nextMajor *= 10;
        $step *= 10;
        $count=1;
        } else
        if (!$this->supress_minor_tickmarks) {
            if( $this->mincolor!="" ) $aImg->PushColor($this->mincolor);
            $aImg->Line($xt+$dx_min,$yt+$dy_min,$xt-$dx_min,$yt-$dy_min);
            if( $this->mincolor!="" ) $aImg->PopColor();
        }
    }
    }
}

class RadarLinearTicks extends LinearTicks
{
//---------------
// CONSTRUCTOR
    public function RadarLinearTicks()
    {
    // Empty
    }

//---------------
// PUBLIC METHODS

    // TODO: Add argument grid
    public function Stroke(&$aImg,&$grid,$aPos,$aAxisAngle,&$aScale,&$aMajPos,&$aMajLabel)
    {
    // Prepare to draw linear ticks
    $maj_step_abs = abs($aScale->scale_factor*$this->major_step);
    $min_step_abs = abs($aScale->scale_factor*$this->minor_step);
    $nbrmaj = floor(($aScale->world_abs_size)/$maj_step_abs);
    $nbrmin = floor(($aScale->world_abs_size)/$min_step_abs);
    $skip = round($nbrmin/$nbrmaj); // Don't draw minor ontop of major

    // Draw major ticks
    $ticklen2=$this->major_abs_size;
    $dx=round(sin($aAxisAngle)*$ticklen2);
    $dy=round(cos($aAxisAngle)*$ticklen2);
    $label=$aScale->scale[0]+$this->major_step;

    $aImg->SetLineWeight($this->weight);

    for ($i=1; $i<=$nbrmaj; ++$i) {
        $xt=round($i*$maj_step_abs*cos($aAxisAngle))+$aScale->scale_abs[0];
        $yt=$aPos-round($i*$maj_step_abs*sin($aAxisAngle));
        $aMajLabel[]=$label;
        $label += $this->major_step;
        $grid[]=$xt;
        $grid[]=$yt;
        $aMajPos[($i-1)*2]=$xt+2*$dx;
        $aMajPos[($i-1)*2+1]=$yt-$aImg->GetFontheight()/2;
        if (!$this->supress_tickmarks) {
        if( $this->majcolor!="" ) $aImg->PushColor($this->majcolor);
        $aImg->Line($xt+$dx,$yt+$dy,$xt-$dx,$yt-$dy);
        if( $this->majcolor!="" ) $aImg->PopColor();
        }
    }

    // Draw minor ticks
    $ticklen2=$this->minor_abs_size;
    $dx=round(sin($aAxisAngle)*$ticklen2);
    $dy=round(cos($aAxisAngle)*$ticklen2);
    if (!$this->supress_tickmarks && !$this->supress_minor_tickmarks) {
        if( $this->mincolor!="" ) $aImg->PushColor($this->mincolor);
        for ($i=1; $i<=$nbrmin; ++$i) {
        if( ($i % $skip) == 0 ) continue;
        $xt=round($i*$min_step_abs*cos($aAxisAngle))+$aScale->scale_abs[0];
        $yt=$aPos-round($i*$min_step_abs*sin($aAxisAngle));
        $aImg->Line($xt+$dx,$yt+$dy,$xt-$dx,$yt-$dy);
        }
        if( $this->mincolor!="" ) $aImg->PopColor();
    }
    }
}



//===================================================
// CLASS RadarAxis
// Description: Implements axis for the spider graph
//===================================================
class RadarAxis extends Axis
{
    public $title_color="navy";
    public $title=null;
//---------------
// CONSTRUCTOR
    public function RadarAxis(&$img,&$aScale,$color=array(0,0,0))
    {
    parent::Axis($img,$aScale,$color);
    $this->len=$img->plotheight;
    $this->title = new Text();
    $this->title->SetFont(FF_FONT1,FS_BOLD);
    $this->color = array(0,0,0);
    }
//---------------
// PUBLIC METHODS
    public function SetTickLabels($l)
    {
    $this->ticks_label = $l;
    }


    // Stroke the axis
    // $pos 			= Vertical position of axis
    // $aAxisAngle = Axis angle
    // $grid			= Returns an array with positions used to draw the grid
    //	$lf			= Label flag, TRUE if the axis should have labels
    public function Stroke($pos,$aAxisAngle,&$grid,$title,$lf)
    {
    $this->img->SetColor($this->color);

    // Determine end points for the axis
    $x=round($this->scale->world_abs_size*cos($aAxisAngle)+$this->scale->scale_abs[0]);
    $y=round($pos-$this->scale->world_abs_size*sin($aAxisAngle));

    // Draw axis
    $this->img->SetColor($this->color);
    $this->img->SetLineWeight($this->weight);
    if( !$this->hide )
        $this->img->Line($this->scale->scale_abs[0],$pos,$x,$y);

    $this->scale->ticks->Stroke($this->img,$grid,$pos,$aAxisAngle,$this->scale,$majpos,$majlabel);

    // Draw labels
    if ($lf && !$this->hide) {
        $this->img->SetFont($this->font_family,$this->font_style,$this->font_size);
        $this->img->SetTextAlign("left","top");
        $this->img->SetColor($this->color);

        // majpos contsins (x,y) coordinates for labels
        for ($i=0; $i<count($majpos)/2; ++$i) {
        if( $this->ticks_label != null )
            $this->img->StrokeText($majpos[$i*2],$majpos[$i*2+1],$this->ticks_label[$i]);
        else
            $this->img->StrokeText($majpos[$i*2],$majpos[$i*2+1],$majlabel[$i]);
        }
    }
    $this->_StrokeAxisTitle($pos,$aAxisAngle,$title);
    }
//---------------
// PRIVATE METHODS

    public function _StrokeAxisTitle($pos,$aAxisAngle,$title)
    {
    $this->title->Set($title);
    $marg=6+$this->title->margin;
    $xt=round(($this->scale->world_abs_size+$marg)*cos($aAxisAngle)+$this->scale->scale_abs[0]);
    $yt=round($pos-($this->scale->world_abs_size+$marg)*sin($aAxisAngle));

    // Position the axis title.
    // dx, dy is the offset from the top left corner of the bounding box that sorrounds the text
    // that intersects with the extension of the corresponding axis. The code looks a little
    // bit messy but this is really the only way of having a reasonable position of the
    // axis titles.
    $h=$this->img->GetFontHeight();
    $w=$this->img->GetTextWidth($title);
    while( $aAxisAngle > 2*M_PI ) $aAxisAngle -= 2*M_PI;
    if( $aAxisAngle>=7*M_PI/4 || $aAxisAngle <= M_PI/4 ) $dx=0;
    if( $aAxisAngle>=M_PI/4 && $aAxisAngle <= 3*M_PI/4 ) $dx=($aAxisAngle-M_PI/4)*2/M_PI;
    if( $aAxisAngle>=3*M_PI/4 && $aAxisAngle <= 5*M_PI/4 ) $dx=1;
    if( $aAxisAngle>=5*M_PI/4 && $aAxisAngle <= 7*M_PI/4 ) $dx=(1-($aAxisAngle-M_PI*5/4)*2/M_PI);

    if( $aAxisAngle>=7*M_PI/4 ) $dy=(($aAxisAngle-M_PI)-3*M_PI/4)*2/M_PI;
    if( $aAxisAngle<=M_PI/4 ) $dy=(1-$aAxisAngle*2/M_PI);
    if( $aAxisAngle>=M_PI/4 && $aAxisAngle <= 3*M_PI/4 ) $dy=1;
    if( $aAxisAngle>=3*M_PI/4 && $aAxisAngle <= 5*M_PI/4 ) $dy=(1-($aAxisAngle-3*M_PI/4)*2/M_PI);
    if( $aAxisAngle>=5*M_PI/4 && $aAxisAngle <= 7*M_PI/4 ) $dy=0;

    if (!$this->hide) {
        $this->title->Stroke($this->img,$xt-$dx*$w,$yt-$dy*$h,$title);
    }
    }


} // Class


//===================================================
// CLASS RadarGrid
// Description: Draws grid for the spider graph
//===================================================
class RadarGrid extends Grid
{
//------------
// CONSTRUCTOR
    public function RadarGrid()
    {
    }

//----------------
// PRIVATE METHODS
    public function Stroke(&$img,&$grid)
    {
    if( !$this->show ) return;
    $nbrticks = count($grid[0])/2;
    $nbrpnts = count($grid);
    $img->SetColor($this->grid_color);
    $img->SetLineWeight($this->weight);
    for ($i=0; $i<$nbrticks; ++$i) {
        for ($j=0; $j<$nbrpnts; ++$j) {
        $pnts[$j*2]=$grid[$j][$i*2];
        $pnts[$j*2+1]=$grid[$j][$i*2+1];
        }
        for ($k=0; $k<$nbrpnts; ++$k) {
        $l=($k+1)%$nbrpnts;
        if( $this->type == "solid" )
            $img->Line($pnts[$k*2],$pnts[$k*2+1],$pnts[$l*2],$pnts[$l*2+1]);
        elseif( $this->type == "dotted" )
            $img->DashedLine($pnts[$k*2],$pnts[$k*2+1],$pnts[$l*2],$pnts[$l*2+1],1,6);
        elseif( $this->type == "dashed" )
            $img->DashedLine($pnts[$k*2],$pnts[$k*2+1],$pnts[$l*2],$pnts[$l*2+1],2,4);
        elseif( $this->type == "longdashed" )
            $img->DashedLine($pnts[$k*2],$pnts[$k*2+1],$pnts[$l*2],$pnts[$l*2+1],8,6);
        }
        $pnts=array();
    }
    }
} // Class


//===================================================
// CLASS RadarPlot
// Description: Plot a spiderplot
//===================================================
class RadarPlot
{
    public $data=array();
    public $fill=false, $fill_color=array(200,170,180);
    public $color=array(0,0,0);
    public $legend="";
    public $weight=1;
    public $linestyle='solid';
    public $mark=null;
//---------------
// CONSTRUCTOR
    public function RadarPlot($data)
    {
    $this->data = $data;
    $this->mark = new PlotMark();
    }

//---------------
// PUBLIC METHODS
    public function Min()
    {
    return Min($this->data);
    }

    public function Max()
    {
    return Max($this->data);
    }

    public function SetLegend($legend)
    {
    $this->legend=$legend;
    }

    public function SetLineStyle($aStyle)
    {
    $this->linestyle=$aStyle;
    }

    public function SetLineWeight($w)
    {
    $this->weight=$w;
    }

    public function SetFillColor($aColor)
    {
    $this->fill_color = $aColor;
    $this->fill = true;
    }

    public function SetFill($f=true)
    {
    $this->fill = $f;
    }

    public function SetColor($aColor,$aFillColor=false)
    {
    $this->color = $aColor;
    if ($aFillColor) {
        $this->SetFillColor($aFillColor);
        $this->fill = true;
    }
    }

    public function GetCSIMareas()
    {
    JpGraphError::Raise("Client side image maps not supported for RadarPlots.");
    }

    public function Stroke(&$img, $pos, &$scale, $startangle)
    {
    $nbrpnts = count($this->data);
    $astep=2*M_PI/$nbrpnts;
    $a=$startangle;

    // Rotate each point to the correct axis-angle
    // TODO: Update for LogScale
    for ($i=0; $i<$nbrpnts; ++$i) {
        //$c=$this->data[$i];
        $cs=$scale->RelTranslate($this->data[$i]);
        $x=round($cs*cos($a)+$scale->scale_abs[0]);
        $y=round($pos-$cs*sin($a));
        /*
          $c=log10($c);
          $x=round(($c-$scale->scale[0])*$scale->scale_factor*cos($a)+$scale->scale_abs[0]);
          $y=round($pos-($c-$scale->scale[0])*$scale->scale_factor*sin($a));
        */
        $pnts[$i*2]=$x;
        $pnts[$i*2+1]=$y;
        $a += $astep;
    }
    if ($this->fill) {
        $img->SetColor($this->fill_color);
        $img->FilledPolygon($pnts);
    }
    $img->SetLineWeight($this->weight);
    $img->SetColor($this->color);
    $img->SetLineStyle($this->linestyle);
    $pnts[]=$pnts[0];
    $pnts[]=$pnts[1];
    $img->Polygon($pnts);
    $img->SetLineStyle('solid'); // Reset line style to default
    // Add plotmarks on top
    if ($this->mark->show) {
        for ($i=0; $i < $nbrpnts; ++$i) {
        $this->mark->Stroke($img,$pnts[$i*2],$pnts[$i*2+1]);
        }
    }

    }

//---------------
// PRIVATE METHODS
    public function GetCount()
    {
    return count($this->data);
    }

    public function Legend(&$graph)
    {
    if( $this->legend=="" ) return;
    if( $this->fill )
        $graph->legend->Add($this->legend,$this->fill_color,$this->mark);
    else
        $graph->legend->Add($this->legend,$this->color,$this->mark);
    }

} // Class

//===================================================
// CLASS RadarGraph
// Description: Main container for a spider graph
//===================================================
class RadarGraph extends Graph
{
    public $posx;
    public $posy;
    public $len;
    public $plots=null, $axis_title=null;
    public $grid,$axis=null;
//---------------
// CONSTRUCTOR
    public function RadarGraph($width=300,$height=200,$cachedName="",$timeout=0,$inline=1)
    {
    $this->Graph($width,$height,$cachedName,$timeout,$inline);
    $this->posx=$width/2;
    $this->posy=$height/2;
    $this->len=min($width,$height)*0.35;
    $this->SetColor(array(255,255,255));
    $this->SetTickDensity(TICKD_NORMAL);
    $this->SetScale("lin");
    $this->SetGridDepth(DEPTH_FRONT);

    }

//---------------
// PUBLIC METHODS
    public function SupressTickMarks($f=true)
    {
        if( ERR_DEPRECATED )
            JpGraphError::Raise('RadarGraph::SupressTickMarks() is deprecated. Use HideTickMarks() instead.');
        $this->axis->scale->ticks->SupressTickMarks($f);
    }

    public function HideTickMarks($aFlag=true)
    {
        $this->axis->scale->ticks->SupressTickMarks($aFlag);
    }

    public function ShowMinorTickmarks($aFlag=true)
    {
        $this->yscale->ticks->SupressMinorTickMarks(!$aFlag);
    }

    public function SetScale($axtype,$ymin=1,$ymax=1)
    {
    if ($axtype != "lin" && $axtype != "log") {
        JpGraphError::Raise("Illegal scale for spiderplot ($axtype). Must be \"lin\" or \"log\"");
    }
    if ($axtype=="lin") {
        $this->yscale = & new LinearScale($ymin,$ymax);
        $this->yscale->ticks = & new RadarLinearTicks();
        $this->yscale->ticks->SupressMinorTickMarks();
    } elseif ($axtype=="log") {
        $this->yscale = & new LogScale($ymin,$ymax);
        $this->yscale->ticks = & new RadarLogTicks();
    }

    $this->axis = & new RadarAxis($this->img,$this->yscale);
    $this->grid = & new RadarGrid();
    }

    public function SetSize($aSize)
    {
    if( $aSize<0.1 || $aSize>1 )
        JpGraphError::Raise("Radar Plot size must be between 0.1 and 1. (Your value=$s)");
    $this->len=min($this->img->width,$this->img->height)*$aSize/2;
    }

    public function SetPlotSize($aSize)
    {
    $this->SetSize($aSize);
    }

    public function SetTickDensity($densy=TICKD_NORMAL)
    {
    $this->ytick_factor=25;
    switch ($densy) {
        case TICKD_DENSE:
        $this->ytick_factor=12;
        break;
        case TICKD_NORMAL:
        $this->ytick_factor=25;
        break;
        case TICKD_SPARSE:
        $this->ytick_factor=40;
        break;
        case TICKD_VERYSPARSE:
        $this->ytick_factor=70;
        break;
        default:
        JpGraphError::Raise("RadarPlot Unsupported Tick density: $densy");
    }
    }

    public function SetPos($px,$py=0.5)
    {
    $this->SetCenter($px,$py);
    }

    public function SetCenter($px,$py=0.5)
    {
    assert($px > 0 && $py > 0 );
    $this->posx=$this->img->width*$px;
    $this->posy=$this->img->height*$py;
    }

    public function SetColor($c)
    {
    $this->SetMarginColor($c);
    }

    public function SetTitles($title)
    {
    $this->axis_title = $title;
    }

    public function Add(&$splot)
    {
    $this->plots[]=$splot;
    }

    public function GetPlotsYMinMax()
    {
    $min=$this->plots[0]->Min();
    $max=$this->plots[0]->Max();
    foreach ($this->plots as $p) {
        $max=max($max,$p->Max());
        $min=min($min,$p->Min());
    }
    if( $min < 0 )
        JpGraphError::Raise("Minimum data $min (Radar plots only makes sence to use when all data points > 0)");

    return array($min,$max);
    }

    // Stroke the Radar graph
    public function Stroke($aStrokeFileName="")
    {
    // Set Y-scale
    if ( !$this->yscale->IsSpecified() && count($this->plots)>0 ) {
        list($min,$max) = $this->GetPlotsYMinMax();
        $this->yscale->AutoScale($this->img,0,$max,$this->len/$this->ytick_factor);
    }
    // Set start position end length of scale (in absolute pixels)
    $this->yscale->SetConstants($this->posx,$this->len);

    // We need as many axis as there are data points
    $nbrpnts=$this->plots[0]->GetCount();

    // If we have no titles just number the axis 1,2,3,...
    if ($this->axis_title==null) {
        for($i=0; $i < $nbrpnts; ++$i )
        $this->axis_title[$i] = $i+1;
    } elseif(count($this->axis_title)<$nbrpnts)
        JpGraphError::Raise("Number of titles does not match number of points in plot.");
    for($i=0; $i<count($this->plots); ++$i )
        if( $nbrpnts != $this->plots[$i]->GetCount() )
        JpGraphError::Raise("Each spider plot must have the same number of data points.");

    if ($this->background_image != "") {
        $this->StrokeFrameBackground();
    } else {
        $this->StrokeFrame();
    }
    $astep=2*M_PI/$nbrpnts;

    // Prepare legends
    for($i=0; $i<count($this->plots); ++$i)
        $this->plots[$i]->Legend($this);
    $this->legend->Stroke($this->img);
    $this->footer->Stroke($this->img);

    if ($this->grid_depth == DEPTH_BACK) {
        // Draw axis and grid
        for ($i=0,$a=M_PI/2; $i < $nbrpnts; ++$i, $a += $astep) {
        $this->axis->Stroke($this->posy,$a,$grid[$i],$this->axis_title[$i],$i==0);
        }
    }

    // Plot points
    $a=M_PI/2;
    for($i=0; $i<count($this->plots); ++$i )
        $this->plots[$i]->Stroke($this->img, $this->posy, $this->yscale, $a);

    if ($this->grid_depth != DEPTH_BACK) {
        // Draw axis and grid
        for ($i=0,$a=M_PI/2; $i < $nbrpnts; ++$i, $a += $astep) {
        $this->axis->Stroke($this->posy,$a,$grid[$i],$this->axis_title[$i],$i==0);
        }
    }
    $this->grid->Stroke($this->img,$grid);
    $this->StrokeTitles();

    // Stroke texts
    if ($this->texts != null) {
        foreach( $this->texts as $t)
        $t->Stroke($this->img);
    }

    // Should we do any final image transformation
    if ($this->iImgTrans) {
        if ( !class_exists('ImgTrans') ) {
        require_once 'jpgraph_imgtrans.php';
        }

        $tform = new ImgTrans($this->img->img);
        $this->img->img = $tform->Skew3D($this->iImgTransHorizon,$this->iImgTransSkewDist,
                         $this->iImgTransDirection,$this->iImgTransHighQ,
                         $this->iImgTransMinSize,$this->iImgTransFillColor,
                         $this->iImgTransBorder);
    }

    // If the filename is given as the special "__handle"
    // then the image handler is returned and the image is NOT
    // streamed back
    if ($aStrokeFileName == _IMG_HANDLER) {
        return $this->img->img;
        } else {
        // Finally stream the generated picture
        $this->cache->PutAndStream($this->img,$this->cache_name,$this->inline,
                       $aStrokeFileName);
    }
    }
} // Class

/* EOF */
