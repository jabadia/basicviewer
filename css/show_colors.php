<html>
  <head>
    <title>Colores</title>
    <style>
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0}
table{border-collapse:collapse;border-spacing:0}
fieldset,img{border:0}
address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal}
li{list-style:none}
caption,th{text-align:left}
h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal}
q:before,q:after{content:""}
abbr,acronym{border:0;font-variant:normal}
sup{vertical-align:text-top}
sub{vertical-align:text-bottom}
input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit}
*:focus{outline:none}
html,body{font-family:"Helvetica Neue",HelveticaNeue,"Helvetica-Neue",Helvetica,"BBAlpha Sans",sans-serif;font-weight:normal;position:relative}

html,body,table{font-size: 12px}
td{ padding: 2px;}    
div{ border: 2px #888 solid; padding: 4px; margin: 2px; background: #eee; }  
    </style>
  </head>
  <body>
<?php

include "dBug.php";

$debug = isset($_GET['debug'])? true : false;
$do_replace = isset($argc); // invocado desde la linea de comandos

class Color
{
  public $color_string_;
  public $red_;
  public $green_;
  public $blue_;
  public $alpha_;
  
  public $hue_;
  public $value_;
  public $saturation_;
  
  public $color_string_type_;
  
  function __construct($color_string)
  {
    $this->color_string_ = $color_string;
    
    if( $this->color_string_[0] == '#')
    {
      $this->color_string_type_ = "hash";
      
      if( strlen($this->color_string_)==4 )
      {
        $this->red_   = hexdec( substr($this->color_string_, 1,1) . substr($this->color_string_, 1,1));  
        $this->green_ = hexdec( substr($this->color_string_, 2,1) . substr($this->color_string_, 2,1));  
        $this->blue_  = hexdec( substr($this->color_string_, 3,1) . substr($this->color_string_, 3,1));  
      }
      else
      {
        assert( strlen($this->color_string_)==7);
          
        $this->red_   = hexdec( substr($this->color_string_, 1,2));  
        $this->green_ = hexdec( substr($this->color_string_, 3,2));  
        $this->blue_  = hexdec( substr($this->color_string_, 5,2));  
      }
    }
    else if( preg_match("/rgba/", $this->color_string_) )
    {
      $this->color_string_type_ = "rgba";
      
      preg_match("/\((.*)\)/", $this->color_string_,$matches);
      $components = explode(",", $matches[1]); 
      $this->red_ = $components[0];
      $this->green_ = $components[1];
      $this->blue_ = $components[2];
      $this->alpha_ = $components[3];
    }
    else if( preg_match("/rgb/", $this->color_string_) )
    {
      $this->color_string_type_ = "rgb";
      
      preg_match("/\((.*)\)/", $this->color_string_,$matches);
      $components = explode(",", $matches[1]); 
      $this->red_ = $components[0];
      $this->green_ = $components[1];
      $this->blue_ = $components[2];
    }
    else 
    {
      $this->color_string_type_ = "named";  
    }
    
    if($this->color_string_type_ != "named")
    {
      list( $this->hue_, $this->saturation_, $this->value_) = $this->rgb2hsv(array($this->red_, $this->green_, $this->blue_));
    }
  }

  function __tostring()
  {
    switch( $this->color_string_type_ )
    {
      case 'hash':
        return sprintf("#%02x%02x%02x", $this->red_, $this->green_, $this->blue_);       
        break;
      case 'named':
        return $this->color_string_;
        break;
      case 'rgb':
        return sprintf("rgb(%d,%d,%d)", $this->red_, $this->green_, $this->blue_);
        break;
      case 'rgba':
        return sprintf("rgba(%d,%d,%d,%d)", $this->red_, $this->green_, $this->blue_, $this->alpha_);
        break;
    }
    return "magenta";
  }

  function rgb2hsv($c) 
  {
    list($r,$g,$b)=$c;
    $r /= 255.0;
    $g /= 255.0;
    $b /= 255.0;
    $v=max($r,$g,$b);
    $t=min($r,$g,$b);
    $s=($v==0)?0:($v-$t)/$v;
    if ($s==0)
      $h=-1;
    else 
    {
      $a=$v-$t;
      $cr=($v-$r)/$a;
      $cg=($v-$g)/$a;
      $cb=($v-$b)/$a;
      $h=($r==$v)?$cb-$cg:(($g==$v)?2+$cr-$cb:(($b==$v)?$h=4+$cg-$cr:0));
      $h=60*$h;
      $h=($h<0)?$h+360:$h;
    }
    //if( $h > 344 && $h < 347) $h = 345;
    return array($h,$s,$v);
  }
  
  // $c = array($hue, $saturation, $brightness)
  // $hue=[0..360], $saturation=[0..1], $brightness=[0..1]
  function hsv2rgb($c) 
  {
    list($h,$s,$v)=$c;
    if ($s==0)
    return array(255.0*$v,255.0*$v,255.0*$v);
    else
    {
      $h=($h%=360)/60;
      $i=floor($h);
      $f=$h-$i;
      $q[0]=$q[1]=$v*(1-$s);
      $q[2]=$v*(1-$s*(1-$f));
      $q[3]=$q[4]=$v;
      $q[5]=$v*(1-$s*$f);
      //return(array($q[($i+4)%5],$q[($i+2)%5],$q[$i%5]));
      return(array(255.0 * $q[($i+4)%6],255.0 * $q[($i+2)%6],255.0 * $q[$i%6])); //[1]
    }
  }
  
  function set_hue($h)
  {
    $this->hue_ = $h;
    list($this->red_, $this->green_, $this->blue_) = $this->hsv2rgb( array($this->hue_, $this->saturation_, $this->value_));
    //printf("%.2f %.2f %.2f\n", $this->red_, $this->green_, $this->blue_);
  } 
}

function compare_hue_value_saturation($c1, $c2)
{
  if( $c1->color_string_type_ == "named" && $c2->color_string_type_ == "named")
    return $c1->color_string_ < $c2->color_string_;
  
  if( $c1->hue_ == $c2->hue_ )
  {
    if( $c1->value_ == $c2->value_ )
    {
      if( $c1->saturation_ == $c2->saturation_)
        return $c1->color_string_ > $c2->color_string_;
        
      return $c1->saturation_ > $c2->saturation_;
    }
    return $c1->value_ > $c2->value_;
  }
  return $c1->hue_ > $c2->hue_;
}

$css = file_get_contents('red.css');

$color_regex = "(#([0-9A-Fa-f]{3,6})\b)" .
"|(aqua)|(black)|(blue)|(fuchsia)|(gray)|(green)|(lime)|(maroon)|(navy)|(olive)|(orange)|(purple)|(red)|(silver)|(teal)|(white)|(yellow)" .
"|(rgb\(\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*\))" .
"|(rgb\(\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*\))" .
//"|(rgba\(\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*,\s*\b([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\b\s*\))" .
//"|(rgba\(\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*,\s*(\d?\d%|100%)+\s*\))" . 
"";

$matches = array();
$matches_count = preg_match_all("/$color_regex/",$css,$matches);

/*
new dBug($color_regex);
new dBug( $matches[0] );
*/

$colors = array();
foreach ($matches[0] as $color_string) 
{
  $color = new Color($color_string);
  $colors[]=$color;
}

usort($colors, "compare_hue_value_saturation");

$newcolors = array();
$newcolors[] = $colors[0];
foreach($colors as $color)
{
  if( $color->color_string_ != $newcolors[count($newcolors)-1]->color_string_)
    $newcolors[]=$color;
}
$colors = $newcolors;

foreach($colors as $color)
{
  $color->set_hue( ($color->hue_+158 ) % 360 );
}


if( $debug )
{
  new dBug($colors);
}
else if( $do_replace )
{
	$search_strings = array();
	$replace_strings = array();
	
  foreach ($colors as $color) 
  {
    $search =  $color->color_string_;
    $replace = $color->__tostring();
    if( $search != $replace && strlen($search) != 4)
    {
      $search_strings[] = $search;
      $replace_strings[] = $replace;
    } 
  }
  /*
  foreach ($colors as $color) 
  {
    $search =  $color->color_string_;
    $replace = $color->__tostring();
    if( $search != $replace && strlen($search) == 4)
    {
      $search_strings[] = $search;
      $replace_strings[] = $replace;
    } 
  }
  */
 
  print "---\n";
  print count($search_strings) . "\n";
  print "---\n";
 	
	$css2 = str_replace($search_strings, $replace_strings, $css);
  $result = file_put_contents("red2.css", $css2);
  echo "<pre>$result</pre>";
}
else
{
  $last_hue = $colors[0]->hue_;
  echo "<div style='float:left;'>";
  echo "<table>";
  foreach($colors as $color)
  {
    if( abs($last_hue-$color->hue_) > 0.9 )
    {
      echo "</table>";
      echo "</div>";
      echo "<div style='float:left;'>";
      echo "<table>";
    }
    $last_hue = $color->hue_;
        
    echo "<tr>";
    echo "<td style='background: {$color->color_string_}; height: 30px; width: 30px; border: 1px black solid;'>&nbsp;</td>";
    echo "<td style='background: $color; height: 30px; width: 30px; border: 1px black solid;'>&nbsp;</td>";
    echo "<td>$color</td>";
    echo "<td style='width: 50px;'>". number_format($color->hue_,2)   ."</td>";
    echo "<td style='width: 50px;'>". number_format($color->saturation_,2) . "</td>";
    echo "<td style='width: 50px;'>". number_format($color->value_,2) ."</td>";
    echo "</tr>";    
  }
  echo "</table>";
  echo "</div>";
}
?>
</body></html>