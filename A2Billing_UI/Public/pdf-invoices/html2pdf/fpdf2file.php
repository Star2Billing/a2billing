<?php
require('fpdf.php');

class FPDF2File extends FPDF
{
var $f;

function Open($file)
{
	if(FPDF_VERSION<'1.53')
		$this->Error('Version 1.53 or above is required by this extension');
	$this->f=fopen($file,'wb');
	if(!$this->f)
		$this->Error('Unable to create output file: '.$file);
	parent::Open();
	$this->_putheader();
}

function Image($file,$x,$y,$w=0,$h=0,$type='',$link='')
{
	if(!isset($this->images[$file]))
	{
		//Retrieve only meta-information
		$a=getimagesize($file);
		if($a===false)
			$this->Error('Missing or incorrect image file: '.$file);
		$this->images[$file]=array('w'=>$a[0],'h'=>$a[1],'type'=>$a[2],'i'=>count($this->images)+1);
	}
	parent::Image($file,$x,$y,$w,$h,$type,$link);
}

function Output()
{
	if($this->state<3)
		$this->Close();
}

function _endpage()
{
	parent::_endpage();
	//Write page to file
	$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
	$p=($this->compress) ? gzcompress($this->buffer) : $this->buffer;
	$this->_newobj();
	$this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
	$this->_putstream($p);
	$this->_out('endobj');
	$this->buffer='';
}

function _newobj()
{
	$this->n++;
	$this->offsets[$this->n]=ftell($this->f);
	$this->_out($this->n.' 0 obj');
}

function _out($s)
{
	if($this->state==2)
		$this->buffer.=$s."\n";
	else
		fwrite($this->f,$s."\n",strlen($s)+1);
}

function _putimages()
{
	$mqr=get_magic_quotes_runtime();
	set_magic_quotes_runtime(0);
	$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
	reset($this->images);
	while(list($file,$info)=each($this->images))
	{
		//Load image
		if($info['type']==2)
			$info=$this->_parsejpg($file);
		elseif($info['type']==3)
			$info=$this->_parsepng($file);
		elseif($info['type']==1 && method_exists($this,'_parsegif'))
			$info=$this->_parsegif($file);
		else
			$this->Error('Unsupported image type: '.$file);
		//Put it into file
		$this->_newobj();
		$this->images[$file]['n']=$this->n;
		$this->_out('<</Type /XObject');
		$this->_out('/Subtype /Image');
		$this->_out('/Width '.$info['w']);
		$this->_out('/Height '.$info['h']);
		if($info['cs']=='Indexed')
			$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
		else
		{
			$this->_out('/ColorSpace /'.$info['cs']);
			if($info['cs']=='DeviceCMYK')
				$this->_out('/Decode [1 0 1 0 1 0 1 0]');
		}
		$this->_out('/BitsPerComponent '.$info['bpc']);
		if(isset($info['f']))
			$this->_out('/Filter /'.$info['f']);
		if(isset($info['parms']))
			$this->_out($info['parms']);
		if(isset($info['trns']) && is_array($info['trns']))
		{
			$trns='';
			for($i=0;$i<count($info['trns']);$i++)
				$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
			$this->_out('/Mask ['.$trns.']');
		}
		$this->_out('/Length '.strlen($info['data']).'>>');
		$this->_putstream($info['data']);
		unset($info['data']);
		$this->_out('endobj');
		//Palette
		if($info['cs']=='Indexed')
		{
			$this->_newobj();
			$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
			$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
			$this->_putstream($pal);
			$this->_out('endobj');
		}
	}
	set_magic_quotes_runtime($mqr);
}

function _putpages()
{
	$nb=$this->page;
	if($this->DefOrientation=='P')
	{
		$wPt=$this->fwPt;
		$hPt=$this->fhPt;
	}
	else
	{
		$wPt=$this->fhPt;
		$hPt=$this->fwPt;
	}
	//Page objects
	for($n=1;$n<=$nb;$n++)
	{
		$this->_newobj();
		$this->_out('<</Type /Page');
		$this->_out('/Parent 1 0 R');
		if(isset($this->OrientationChanges[$n]))
			$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$hPt,$wPt));
		$this->_out('/Resources 2 0 R');
		if(isset($this->PageLinks[$n]))
		{
			//Links
			$annots='/Annots [';
			foreach($this->PageLinks[$n] as $pl)
			{
				$rect=sprintf('%.2f %.2f %.2f %.2f',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
				$annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
				if(is_string($pl[4]))
					$annots.='/A <</S /URI /URI '.$this->_textstring($pl[4]).'>>>>';
				else
				{
					$l=$this->links[$pl[4]];
					$h=isset($this->OrientationChanges[$l[0]]) ? $wPt : $hPt;
					$annots.=sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]>>',2+$nb+$l[0],$h-$l[1]*$this->k);
				}
			}
			$this->_out($annots.']');
		}
		$this->_out('/Contents '.(2+$n).' 0 R>>');
		$this->_out('endobj');
	}
	//Page root
	$this->offsets[1]=ftell($this->f);
	$this->_out('1 0 obj');
	$this->_out('<</Type /Pages');
	$kids='/Kids [';
	for($n=1;$n<=$nb;$n++)
		$kids.=(2+$nb+$n).' 0 R ';
	$this->_out($kids.']');
	$this->_out('/Count '.$nb);
	$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
	$this->_out('>>');
	$this->_out('endobj');
}

function _putresources()
{
	$this->_putfonts();
	$this->_putimages();
	//Resource dictionary
	$this->offsets[2]=ftell($this->f);
	$this->_out('2 0 obj');
	$this->_out('<<');
	$this->_putresourcedict();
	$this->_out('>>');
	$this->_out('endobj');
}

function _putcatalog()
{
	$this->_out('/Type /Catalog');
	$this->_out('/Pages 1 0 R');
	$n=3+$this->page;
	if($this->ZoomMode=='fullpage')
		$this->_out('/OpenAction ['.$n.' 0 R /Fit]');
	elseif($this->ZoomMode=='fullwidth')
		$this->_out('/OpenAction ['.$n.' 0 R /FitH null]');
	elseif($this->ZoomMode=='real')
		$this->_out('/OpenAction ['.$n.' 0 R /XYZ null null 1]');
	elseif(!is_string($this->ZoomMode))
		$this->_out('/OpenAction ['.$n.' 0 R /XYZ null null '.($this->ZoomMode/100).']');
	if($this->LayoutMode=='single')
		$this->_out('/PageLayout /SinglePage');
	elseif($this->LayoutMode=='continuous')
		$this->_out('/PageLayout /OneColumn');
	elseif($this->LayoutMode=='two')
		$this->_out('/PageLayout /TwoColumnLeft');
}

function _enddoc()
{
	$this->_putpages();
	$this->_putresources();
	//Info
	$this->_newobj();
	$this->_out('<<');
	$this->_putinfo();
	$this->_out('>>');
	$this->_out('endobj');
	//Catalog
	$this->_newobj();
	$this->_out('<<');
	$this->_putcatalog();
	$this->_out('>>');
	$this->_out('endobj');
	//Cross-ref
	$o=ftell($this->f);
	$this->_out('xref');
	$this->_out('0 '.($this->n+1));
	$this->_out('0000000000 65535 f ');
	for($i=1;$i<=$this->n;$i++)
		$this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
	//Trailer
	$this->_out('trailer');
	$this->_out('<<');
	$this->_puttrailer();
	$this->_out('>>');
	$this->_out('startxref');
	$this->_out($o);
	$this->_out('%%EOF');
	$this->state=3;
	fclose($this->f);
}
}
