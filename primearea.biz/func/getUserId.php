<?php
include "mysql.php";

if(mb_strlen($_POST['crypt']) > 12){
	

   $request = readMysql("select u.id 
                        from user u
                        inner join usersession us
                        on us.userid = u.id
                        WHERE us.crypt = '".realMysql($_POST['crypt'], 40)."'");

   exit(mysql_result($request,0,0));
}
exit("0");
?>