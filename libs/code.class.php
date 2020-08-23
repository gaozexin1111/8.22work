<?php
namespace libs;
//1.指定输出的格式
//2. 创建画布
class code{
    public $type = "png";
    public $width = 160;
    public $height = 50;
    public $codeLen = 4;
    public $seed = "abcdefhjkmnprstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ2345678";

    public $fontSize = array("min"=>25,"max"=>35);
    public $fontAngle = array("min"=>-15,"max"=>15);
    public $lineNum = array("min"=>2,"max"=>4);
    public $lineWidth = array("min"=>1,"max"=>4);
    public $pixNum = array("min"=>80,"max"=>150);


    private function createCanvas(){
        $this->image = imagecreatetruecolor($this->width,$this->height);
        imagefill($this->image,0,0,$this->setColor());
    }

    private function setColor($type="background"){
        //设置随机值
        $r = $type=="background"?mt_rand(0,125):mt_rand(126,255);
        $g = $type=="background"?mt_rand(0,125):mt_rand(126,255);
        $b = $type=="background"?mt_rand(0,125):mt_rand(126,255);

        return imagecolorallocate($this->image,$r,$g,$b);
    }

    private function getText(){
        $str="";
        for($i=0;$i<$this->codeLen;$i++){
            $str.=$this->seed[mt_rand(0,strlen($this->seed)-1)];
        }
        return $str;
    }


    private function setText(){
//        $font = 'D:\Wampserver\www\八月\mvcone\application\static\font\msyh.ttc';
        $font = 'C:\Windows\Fonts\msyh.ttc';

        $str = $this->getText();
        $this->str = strtolower($str);
        for($i=0;$i<$this->codeLen;$i++){
            $size = mt_rand($this->fontSize["min"],$this->fontSize["max"]);
            $angle = mt_rand($this->fontAngle["min"],$this->fontAngle["max"]);
            $space = $this->width/($this->codeLen*2+1);

            $box = imagettfbbox($size,$angle,$font,$str[$i]);

            imagettftext($this->image,$size,$angle,mt_rand(max(($i*2-1)*$space,0)+5,($i*2+1)*$space-5),$box[0]-$box[7]+mt_rand(0,$this->height-($box[0]-$box[7])),$this->setColor("a"),$font,$str[$i]);
        }


    }

    private function setLine(){
        $num = mt_rand($this->lineNum["min"],$this->lineNum["max"]);
        for($i=0;$i<$num;$i++){
            $x1 = mt_rand(0,($this->width)/2);
            $x2 = mt_rand(($this->width)/2,$this->width);
            $y1 = mt_rand(0,$this->height);
            $y2 = mt_rand(0,$this->height);
            imagesetthickness($this->image,mt_rand($this->lineWidth["min"],$this->lineWidth["max"]));
            imageline($this->image,$x1,$y1,$x2,$y2,$this->setColor());
        }

    }

    private function setPix(){
        $num = mt_rand($this->pixNum["min"],$this->pixNum["max"]);
        for($i=0;$i<$num;$i++){
            $x1 = mt_rand(0,($this->width)/2);
            $x2 = mt_rand(($this->width)/2,$this->width);
            $y1 = mt_rand(0,$this->height);
            $y2 = mt_rand(0,$this->height);
            imagesetpixel($this->image,mt_rand(0,$this->width),mt_rand(0,$this->height),$this->setColor());
        }
    }

    function out(){
        header("content-type:image/".$this->type);

        //1.创建画布
        $this->createCanvas();

        //2.写文字
        $this->setText();

        //3.线条干扰
        $this->setLine();
        //4.画像素点
        $this->setPix();
        $outtype = "image".$this->type;

        //5.写入cookie
//        setcookie("code",$this->str,0,"/");
        $_SESSION["code"]=$this->str;
        $outtype($this->image);
        imagedestroy($this->image);

    }


}

