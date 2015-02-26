<?php

/*++++++++++++++++++++++++++++++++++++
程序名称：IP解析程序
程序功能：基于QQ的二进制数据库QQWry.Dat
程序作者：strongc
使用方法：

Code from http://www.oschina.net/code/snippet_99617_4950
这个代码其实是从一个PECL的C++代码改过来的。
原版（c++）在这 http://pecl.php.net/get/qqwry-0.1.0.tgz

请将文件 QQWry.Dat 置于当前目录中
或者可以用修改
define('__QQWRY__' , dirname(__FILE__).".\QQWry.Dat");
语句自定义QQWry.Dat路径

#实例+++++++++++++++++++++++++++++++
$ip="202.201.48.1";
$QQWry=new QQWry;
$ifErr=$QQWry->QQWry($ip);
echo "$QQWry->Country$QQWry->Local";
+++++++++++++++++++++++++++++++++++++*/

define('__QQWRY__', dirname(__FILE__) . '/qqwry.dat');
class QQWry
{
    var $StartIP = 0;
    var $EndIP = 0;
    var $Country = '';
    var $Local = '';

    var $CountryFlag = 0; // 标识 Country位置
        // 0x01,随后3字节为Country偏移,没有Local
        // 0x02,随后3字节为Country偏移,接着是Local
        // 其他,Country,Local,Local有类似的压缩。可能多重引用。

    var $fp;

    var $FirstStartIp = 0;
    var $LastStartIp = 0;
    var $EndIpOff = 0;

    function getStartIp($RecNo)
    {
        $offset = $this->FirstStartIp + $RecNo * 7;
        @fseek($this->fp, $offset, SEEK_SET);
        $buf = fread($this->fp, 7);
        $this->EndIpOff = ord($buf[4]) + (ord($buf[5]) * 256) + (ord($buf[6]) * 256 * 256);
        $this->StartIp = ord($buf[0]) + (ord($buf[1]) * 256) + (ord($buf[2]) * 256 * 256) + (ord($buf[3]) * 256 * 256 * 256);
        return $this->StartIp;
    }

    function getEndIp()
    {
        @fseek($this->fp, $this->EndIpOff, SEEK_SET);
        $buf = fread($this->fp, 5);
        $this->EndIp = ord($buf[0]) + (ord($buf[1]) * 256) + (ord($buf[2]) * 256 * 256) + (ord($buf[3]) * 256 * 256 * 256);
        $this->CountryFlag = ord($buf[4]);
        return $this->EndIp;
    }

    function getCountry()
    {
        switch($this->CountryFlag)
        {
            case 1:
            case 2:
                $this->Country = $this->getFlagStr($this->EndIpOff + 4);
                $this->Local = (1 == $this->CountryFlag) ? '' : $this->getFlagStr($this->EndIpOff + 8);
                break ;
            default:
                $this->Country = $this->getFlagStr($this->EndIpOff + 4);
                $this->Local = $this->getFlagStr(ftell($this->fp));
        }
    }

    function getFlagStr($offset)
    {
        $flag = 0;

        while(1)
        {
            @fseek($this->fp, $offset, SEEK_SET);
            $flag = ord(fgetc($this->fp));

            if($flag == 1 || $flag == 2)
            {
                $buf = fread($this->fp, 3);

                if($flag == 2)
                {
                    $this->CountryFlag = 2;
                    $this->EndIpOff = $offset - 4;
                }

                $offset = ord($buf[0]) + (ord($buf[1]) * 256) + (ord($buf[2]) * 256 * 256);
            }
            else
                break;
        }

        if($offset < 12) return '';

        @fseek($this->fp, $offset, SEEK_SET);

        return $this->getStr();
    }

    function getStr()
    {
        $str = '';

        while(1)
        {
            $c = fgetc($this->fp);

            if(ord($c[0]) == 0) break;

            $str .= $c;
        }

        return $str;
    }

    function QQwry($dotip = '') {
        if (strlen ($dotip) == 0) {
            $this->Country = 'Empty';
			return;
		}

        if (preg_match("/^(127)/", $dotip)) {
            $this->Country = 'Localhost';
            return;
        }
        else if (preg_match("/^(192)/", $dotip)) {
            $this->Country = 'LAN';
            return;
        }

        $nRet;
        $ip = $this->IpToInt ($dotip);
        $this->fp = fopen(__QQWRY__, "rb");

        if ($this->fp == NULL) {
            $this->Country = "OpenFileError";
            return 1;
        }

        @fseek ($this->fp, 0, SEEK_SET);
        $buf = fread ($this->fp, 8);
        $this->FirstStartIp = ord($buf[0]) + (ord($buf[1]) * 256) + (ord($buf[2]) * 256 * 256) + (ord($buf[3]) * 256 * 256 * 256);
        $this->LastStartIp = ord($buf[4]) + (ord($buf[5]) * 256) + (ord($buf[6]) * 256 * 256) + (ord($buf[7]) * 256 * 256 * 256);

        $RecordCount = floor(($this->LastStartIp - $this->FirstStartIp) / 7);

        if($RecordCount <= 1) {
            $this->Country = "FileDataError";
            fclose($this->fp);
            return 2;
        }

        $RangB = 0;
        $RangE = $RecordCount;

        // Match ...
        while($RangB < $RangE - 1) {
            $RecNo = floor(($RangB + $RangE) / 2);
            $this->getStartIp($RecNo) ;

            if($ip == $this->StartIp) {
                $RangB = $RecNo;
                break;
            }

            if($ip > $this->StartIp)
				$RangB = $RecNo;
            else
				$RangE = $RecNo;
        }

        $this->getStartIp($RangB);
        $this->getEndIp();

        if(($this->StartIp <= $ip) && ($this->EndIp >= $ip)) {
            $this->getCountry();
        }
        else {
            $this->Country = '未知';
            $this->Local = '';
        }

        fclose ($this->fp);
    }

    function IpToInt ($Ip) {
        $array = explode ('.', $Ip);
        $Int = ($array[0] * 256 * 256 * 256) + ($array[1] * 256 * 256) + ($array[2] * 256) + $array[3];

        return $Int;
    }
}

// $ip="127.0.0.1";
// $QQWry = new QQWry;
// $ifErr = $QQWry->QQWry($ip);
// echo $ip . "$QQWry->Country$QQWry->Local\n";

