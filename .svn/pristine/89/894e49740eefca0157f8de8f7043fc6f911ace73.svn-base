<?php namespace JerryLib\System;

    /**
     * Class Email
     * 需要配置sendmail才能够发邮件
     */
	class Email {
		//---设置全局变量  
		static $mailTo = ''; // 收件人
		static $mailCC = ''; // 抄送
		static $mailBCC = ''; // 秘密抄送
		static $mailFrom = ''; // 发件人
		static $mailSubject = ''; // 主题
		static $mailText = ''; // 文本格式的信件主体
		static $mailHTML = ''; // html格式的信件主体
		static $mailAttachments = ''; // 附件
		
		/**************************************************
		函数setTo($inAddress) :用于处理邮件的地址 参数 $inAddress  
		为包涵一个或多个字串,email地址变量,使用逗号来分割多个邮件地址  
		默认返回值为true  
		**********************************************************/  
		public static function setTo($inAddress) {  
			$addressArray = explode(',', $inAddress);  //--用explode()函数根据”,”对邮件地址进行分割
			for($i=0; $i<count($addressArray); $i++) {
				if(self::checkEmail($addressArray[$i]) == false) 
					return false; 
			}  //--通过循环对邮件地址的合法性进行检查  
			//--所有合法的email地址存入数组中  
			self::$mailTo = implode($addressArray, ',');
			return true; 
		}  
		
		/**************************************************  
		函数 setCC($inAddress) 设置抄送人邮件地址  
		参数 $inAddress 为包涵一个或多个邮件地址的字串,email地址变量,  
		使用逗号来分割多个邮件地址 默认返回值为true  
		**************************************************************/  
		public static function setCC($inAddress) {
			$addressArray = explode(",", $inAddress);  //--用explode()函数根据”,”对邮件地址进行分割  
			for($i=0;$i<count($addressArray);$i++) { 
				if(self::checkEmail($addressArray[$i]) == false) 
					return false; 
			}  //--通过循环对邮件地址的合法性进行检查  
			self::$mailCC = implode($addressArray, ",");  //--所有合法的email地址存入数组中  
			return true; 
		}  
		
		/**************************************************
		函数setBCC($inAddress) 设置秘密抄送地址 参数 $inAddress 
		为包涵一个或多个邮件地址的字串,email地址变量,使用逗号来分割
		多个邮件地址 默认返回值为true
		**************************************************************/  
		public static function setBCC($inAddress) {  
			$addressArray = explode(",", $inAddress);  //--用explode()函数根据”,”对邮件地址进行分割  
			for($i=0;$i<count($addressArray);$i++) {  //--通过循环对邮件地址的合法性进行检查  
				if(self::checkEmail($addressArray[$i]) == false)  
					return false;
			}
			self::$mailBCC = implode($addressArray, ",");  //--所有合法的email地址存入数组中  
			return true;  
		}  
		
		/**************************************************
		函数setFrom($inAddress):设置发件人地址 参数 $inAddress 为包涵邮件  
		地址的字串默认返回值为true  
		**************************************************************/ 
		public static function setFrom($inAddress) {  
			if(self::checkEmail($inAddress)) {  
				self::$mailFrom = $inAddress;  
				return true;  
			} 
			return false; 
		}  
		
		/**************************************************
		函数 setSubject($inSubject) 用于设置邮件主题参数$inSubject为字串,  
		默认返回的是true  
		**************************************************************/ 
		public static function setSubject($inSubject) {  
			if(strlen(trim($inSubject))>0) {  
				self::$mailSubject = preg_replace("/n/", "", $inSubject);  
				return true; 
			}  
			return false; 
		}  
		
		/**************************************************
		函数setText($inText) 设置文本格式的邮件主体参数 $inText 为文本内容默  
		认返回值为true  
		**************************************************************/ 
		public static function setText($inText) {  
			if(strlen(trim($inText))>0) {  
				self::$mailText = $inText;  
				return true; 
			}  
			return false;  
		}  
		
		/**************************************************
		函数setHTML($inHTML) 设置html格式的邮件主体参数$inHTML为html格式,  
		默认返回值为true  
		**************************************************************/ 
		public static function setHTML($inHTML) {  
			if(strlen(trim($inHTML))>0) {  
				self::$mailHTML = $inHTML;  
				return true; 
			}  
			return false; 
		}  
		
		/**************************************************
		函数 setAttachments($inAttachments) 设置邮件的附件 参数$inAttachments 
		为一个包涵目录的字串,也可以包涵多个文件用逗号进行分割 默认返回值为true  
		**************************************************************/ 
		public static function setAttachments($inAttachments) {  
			if(strlen(trim($inAttachments))>0) {  
				self::$mailAttachments = $inAttachments;  
				return true; 
			}
			return false; 
		}
		
		/**************************************************
		函数 checkEmail($inAddress) :这个函数我们前面已经调用过了,主要就是  
		用于检查email地址的合法性    
		**************************************************************/ 
		public static function checkEmail($inAddress) {  
			return (preg_match("/^[^@ ]+@([a-zA-Z0-9-]+.)+([a-zA-Z0-9-]{2}|net|com|gov|mil|org|edu|int)$/", $inAddress));  
		}  
		
		/**************************************************
		函数loadTemplate($inFileLocation,$inHash,$inFormat) 读取临时文件并且  
		替换无用的信息参数$inFileLocation用于定位文件的目录  
		$inHash 由于存储临时的值 $inFormat 由于放置邮件主体  
		**************************************************************/ 
		public static function loadTemplate($inFileLocation,$inHash,$inFormat) {
			/* 比如邮件内有如下内容: Dear ~!UserName~,  
			Your address is ~!UserAddress~ */  
			//--其中”~!”为起始标志”~”为结束标志  
			$templateDelim = "~";  
			$templateNameStart = "!";  
			//--找出这些地方并把他们替换掉  
			$templateLineOut = ""; 
			if($templateFile = fopen($inFileLocation, "r")) {  //--打开临时文件  
				while(!feof($templateFile)) {  
					$templateLine = fgets($templateFile, 1000);  
					$templateLineArray = explode($templateDelim, $templateLine);  
					for( $i=0; $i<count($templateLineArray); $i++){
						if(strcspn($templateLineArray[$i],$templateNameStart)==0){  //--寻找起始位置  
							$hashName = substr($templateLineArray[$i], 1);  //--替换相应的值  
							$templateLineArray[$i] = preg_replace("/$hashName/", (string)$inHash[$hashName], $hashName);  //--替换相应的值  
						}  
					}  
					//--输出字符数组并叠加  
					$templateLineOut .= implode($templateLineArray, "");  
				} 
				fclose($templateFile);  
				//--设置主体格式(文本或html)  
				if(strtoupper($inFormat) == "TEXT")
					return self::setText($templateLineOut);
				else if(strtoupper($inFormat) == "HTML")  
					return self::setHTML($templateLineOut);
			} 
			return false;  
		}  
		
		/**************************************************
		函数 getRandomBoundary($offset) 返回一个随机的边界值  
		参数 $offset 为整数 – 用于多管道的调用 返回一个md5()编码的字串  
		**************************************************************/ 
		public static function getRandomBoundary($offset = 0) {
            mt_srand(time()+$offset);  //--随机数生成
			return "----".(md5(mt_rand())); //--返回 md5 编码的32位 字符长度的字串
		}  
		
		/**************************************************
		函数: getContentType($inFileName)用于判断附件的类型  
		**************************************************************/ 
		public static function getContentType($inFileName) {  
			$inFileName = basename($inFileName);  //--去除路径  
			if(strrchr($inFileName, ".") == false) {  //--去除没有扩展名的文件  
				return "application/octet-stream";  
			}  
			$extension = strrchr($inFileName, ".");  //--提区扩展名并进行判断
			switch($extension) {  
				case ".gif": 
					return "image/gif";  
				case ".gz": 
					return "application/x-gzip";  
				case ".htm":
					return "text/html";  
				case ".html": 
					return "text/html";  
				case ".jpg": 
					return "image/jpeg";  
				case ".tar": 
					return "application/x-tar";  
				case ".txt": 
					return "text/plain";  
				case ".zip": 
					return "application/zip";  
				default: 
					return "application/octet-stream";  
			}  
			return "application/octet-stream";  
		}  
		
		/**************************************************
		函数formatTextHeader把文本内容加上text的文件头  
		**************************************************************/ 
		public static function formatTextHeader() { 
			$outTextHeader = "";  
			$outTextHeader .= "Content-Type: text/plain; charset=us-asciin";  
			$outTextHeader .= "Content-Transfer-Encoding: 7bitnn"."\n";  
			$outTextHeader .= self::$mailText."\n";  
			return $outTextHeader;  
		} 
		
		/**************************************************
		函数formatHTMLHeader()把邮件主体内容加上html的文件头  
		**************************************************************/ 
		public static function formatHTMLHeader() {  
			$outHTMLHeader = "";  
			$outHTMLHeader .= "Content-Type: text/html; charset=us-asciin";  
			$outHTMLHeader .= "Content-Transfer-Encoding: 7bitnn"."\n";  
			$outHTMLHeader .= self::$mailHTML."\n";  
			return $outHTMLHeader;  
		}  
		
		/**************************************************
		函数 formatAttachmentHeader($inFileLocation) 把邮件中的附件标识出来  
		**************************************************************/ 
		public static function formatAttachmentHeader($inFileLocation) {
			$outAttachmentHeader = "";  
			$contentType = self::getContentType($inFileLocation);  //--用上面的函数getContentType($inFileLocation)得出附件类型
			if(preg_match("/text/", $contentType)) {  //--如果附件是文本型则用标准的7位编码  
				$outAttachmentHeader .= "Content-Type: ".$contentType.";n";  
				$outAttachmentHeader .= ' name="'.basename($inFileLocation).'"'."n";  
				$outAttachmentHeader .= "Content-Transfer-Encoding: 7bitn";  
				$outAttachmentHeader .= "Content-Disposition: attachment;n";  
				$outAttachmentHeader .= ' filename="'.basename($inFileLocation).'"'."nn";  
				$textFile = fopen($inFileLocation, "r");  
				while(!feof($textFile)) {  
					$outAttachmentHeader .= fgets($textFile, 1000);  
				}  
				fclose($textFile);  //--关闭文件 
				$outAttachmentHeader .= "n";  
			} else { //--非文本格式则用64位进行编码  
				$outAttachmentHeader .= "Content-Type: ".$contentType.";n";  
				$outAttachmentHeader .= ' name="'.basename($inFileLocation).'"'."n";  
				$outAttachmentHeader .= "Content-Transfer-Encoding: base64n";  
				$outAttachmentHeader .= "Content-Disposition: attachment;n";  
				$outAttachmentHeader .= ' filename="'.basename($inFileLocation).'"'."nn";  
				//--调用外部命令uuencode进行编码  
				exec("uuencode -m $inFileLocation nothing_out", $returnArray);  
				for($i=1; $i<(count($returnArray)); $i++) {  
					$outAttachmentHeader .= $returnArray[$i]."n";  
				}
			} 
			return $outAttachmentHeader;  
		}  
		
		/**************************************************
		函数 send()用于发送邮件，发送成功返回值为true  
		**************************************************************/ 
		public static function send() {
			$mailHeader = "";  //--设置邮件头为空
			if(self::$mailCC != "")  //--添加抄送人
				$mailHeader .= "CC: ".self::$mailCC."\n";  
			if(self::$mailBCC != "")  //--添加秘密抄送人,这里会使发件人那里变成代发
				$mailHeader .= "BCC: ".self::$mailBCC."\n";  
			if(self::$mailFrom != "")  //--添加发件人  
				$mailHeader .= "FROM: ".self::$mailFrom."\n";  
			//---------------------------邮件格式------------------------------//
			//--文本格式  
			if(self::$mailText != "" && self::$mailHTML == "" && self::$mailAttachments == "") { 
				return mail(self::$mailTo, self::$mailSubject, self::$mailText, $mailHeader);
			}
			//--html和text格式  
			else if(self::$mailText != "" && self::$mailHTML != "" && self::$mailAttachments == "") {  
				$bodyBoundary = self::getRandomBoundary();
				$textHeader = self::formatTextHeader();
				$htmlHeader = self::formatHTMLHeader();
				//--设置 MIME-版本  
				$mailHeader .= "MIME-Version: 1.0"."\n";  
				$mailHeader .= "Content-Type: multipart/alternative;"."\n";  
				$mailHeader .= 'boundary="'.$bodyBoundary.'"'."\n";  
				//--添加邮件主体和边界  
				$mailHeader .= "--".$bodyBoundary."\n";  
				$mailHeader .= $textHeader."\n";  
				$mailHeader .= "--".$bodyBoundary."\n";  
				//--添加html标签  
				$mailHeader .= $htmlHeader."\n";  
				$mailHeader .= "--".$bodyBoundary."--";  
				//--发送邮件  
				return mail(self::$mailTo, self::$mailSubject, "", $mailHeader);  
			}  
			//--文本加html加附件  
			else if(self::$mailText != "" && self::$mailHTML != "" && self::$mailAttachments != "") {
				$attachmentBoundary = self::getRandomBoundary();
				$mailHeader .= "Content-Type: multipart/mixed;n";
				$mailHeader .= ' boundary="'.$attachmentBoundary.'"'."nn";
				$mailHeader .= "This is a multi-part message in MIME format.n";
				$mailHeader .= "--".$attachmentBoundary."n";
				$bodyBoundary = self::getRandomBoundary(1);
				$textHeader = self::formatTextHeader();
				$htmlHeader = self::formatHTMLHeader();
				$mailHeader .= "MIME-Version: 1.0n";
				$mailHeader .= "Content-Type: multipart/alternative;n";
				$mailHeader .= ' boundary="'.$bodyBoundary.'"';
				$mailHeader .= "nnn";
				$mailHeader .= "--".$bodyBoundary."n";
				$mailHeader .= $textHeader;
				$mailHeader .= "--".$bodyBoundary."n";
				$mailHeader .= $htmlHeader;
				$mailHeader .= "n--".$bodyBoundary."--";
				//--获取附件值
				$attachmentArray = explode(",", self::$mailAttachments);
				//--根据附件的个数进行循环
				for($i=0; $i<count($attachmentArray); $i++) {
					//--分割
					$mailHeader .= "n--".$attachmentBoundary."n";
					//--附件信息
					$mailHeader .= self::formatAttachmentHeader($attachmentArray[$i]);
				}
				$mailHeader .= "--".$attachmentBoundary."--";
				return mail(self::$mailTo, self::$mailSubject, "", $mailHeader);
			}
			return false;  
		}
	}