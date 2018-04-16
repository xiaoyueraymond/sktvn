<?php 
$link=mysql_connect("localhost","abcd","123"); 
if(!$link) echo "FAILD!连接错误，用户名密码不对"; 
else echo "OK!可以连接"; 
phpinfo()
?> 

mysql
SELECT User, Host, Password FROM mysql.user;
GRANT ALL PRIVILEGES ON *.* TO 'abcd'@'localhost' IDENTIFIED BY '123';
FLUSH PRIVILEGES;

安装论坛
		[https://blog.csdn.net/doegoo/article/details/50724495]
		mv upload/* /var/www/html/
		GRANT ALL PRIVILEGES ON *.* TO 'root'@'loaclhost' IDENTIFIED BY '123';

       
        2.解压包

		https://gitee.com/ComsenzDiscuz/DiscuzX/
        yum install -y zip unzip
        unzip ComsenzDiscuz-DiscuzX-master.zip 
		3.把解压后的upload文件夹下的所有文件复制到/var/www/html/

		cp -r upload/* /var/www/html/

		4.给html文件及子文件赋权限

		chmod -R 777 /var/www/html

		5.重启Apache

		/etc/init.d/httpd restart

		访问http://ip:<port>/install开始安装Discuz,出现以下页面后，根据页面的提示，完成discuz的安装
			
		10.100.10.13  用户名密码 admin /ADMIN