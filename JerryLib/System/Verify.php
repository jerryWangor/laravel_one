<?php namespace JerryLib\System;
	//验证码类
	use Illuminate\Support\Facades\Session;

    class Verify {
		
		private static $charset;//随机因子
		private static $code;//验证码
		private static $codelen = 4;//验证码长度
		private static $width = 80;//宽度
		private static $height = 40;//高度
		private static $img;//图形资源句柄
		private static $fonturl;//指定的字体
		private static $fontsize = 20;//指定字体大小
		private static $fontcolor;//指定字体颜色
		
		//生成随机码
		private static function createCode() {
			$_len = strlen(self::$charset)-1;
			for($i=0;$i<self::$codelen;$i++) {
				self::$code .= self::$charset[mt_rand(0,$_len)];
			}
		}
		//生成背景
		private static function createBg() {
			self::$img = imagecreatetruecolor(self::$width, self::$height); // 创建一个图片资源句柄
			$color = imagecolorallocate(self::$img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255)); // 为图像资源分配颜色
			imagefilledrectangle(self::$img,0,self::$height,self::$width,0,$color); // 填充矩形区域的颜色
		}
		//生成文字
		private static function createFont() {
			$_x = self::$width / self::$codelen;
			for($i=0;$i<self::$codelen;$i++) {
				self::$fontcolor = imagecolorallocate(self::$img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
				imagettftext(self::$img,self::$fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),self::$height / 1.4,self::$fontcolor,self::$fonturl,self::$code[$i]);
			}
		}
		//生成线条、雪花
		private static function createLine() {
		 //线条
			for($i=0;$i<6;$i++) {
				$color = imagecolorallocate(self::$img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
				imageline(self::$img,mt_rand(0,self::$width),mt_rand(0,self::$height),mt_rand(0,self::$width),mt_rand(0,self::$height),$color);
			}
		 //雪花
			for($i=0;$i<100;$i++) {
				$color = imagecolorallocate(self::$img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
				imagestring(self::$img,mt_rand(1,5),mt_rand(0,self::$width),mt_rand(0,self::$height),'*',$color);
			}
		}
		//输出
		private static function outPut() {
			header('Content-type:image/png');
			imagepng(self::$img);
			imagedestroy(self::$img);
		}
		//对外生成图像验证码
		public static function doimg($codelen = 4, $width = 80, $height = 40, $fontsize = 16, $fonturl = '') {
			self::$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
			self::$fonturl = !empty($fonturl) ? $fonturl : SERVER_ROOT.'/font/Elephant.ttf';//注意字体路径要写对，否则显示不了图片
			
			self::createBg();
			self::createCode();
			self::createLine();
			self::createFont();
			self::outPut();
			Session::put('verify', self::getCode());
		}

        //对外生成短信验证码
        public static function donumber($telephone) {
			self::$charset = '0123456789';
            self::$codelen = 6;
            self::createCode();
            if(isset($_SESSION)) {
                $_SESSION[$telephone] = self::getCode();
            }
            return self::getCode();
        }

		//获取验证码
		public static function getCode() {
			return strtolower(self::$code);
		}
	}
	