<?php
header("content-type:text/html;charset=UTF-8");
require_once "config/config.php";
require_once "functions/common.func.php";
require_once 'functions/mysql.func.php';
require_once 'swiftmailer-master/lib/swift_required.php';

require_once 'db_sqlserver.php';


$act=$_REQUEST['act'];
$username=$_POST['username'];
$password=$_POST['password'];
switch($act){
    case 'reg':
        //关闭事务的自动提交
        odbc_autocommit(db_con(),false);
        //current time
        $regTime=time();
        //get Email
        $email=$_POST['email'];
        //create a Token: use username,password,current time to create a md5 codes;
        $token=md5($username.$password.$regTime);
        //create a Token Exptime, it will be overtime in one day.
        $token_exptime=$regTime+24*3600;
        //insert into table to database
        $res=db_exec("insert into Client(UserName,password,Email,Token,Token_Exptime,RegTime) VALUES('$username','$password','$email','$token',$token_exptime,$regTime)");
        $transport=Swift_SmtpTransport::newInstance("smtp.qq.com",25);
        $transport->setUsername("dongshiheng945@qq.com");
        $transport->setPassword("pangchuwei8");
        //create a object for send email
        $mailer=Swift_Mailer::newInstance($transport);
        //email message object
        $message=Swift_Message::newInstance();
        //who send
        $message->setFrom(array("dongshiheng945@qq.com"));
        //send to where
        $message->setTo($email);
        //set theme
        $activeStr="?act=active&username={$username}&token={$token}";
        $message->setSubject('Please active your account');
        $url="http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$activeStr;
        $urlEncode=urlencode($url);
//         echo $urlEncode;
        $emailBody=<<<EOF
        {$username}, welcome to use our website, please click the link to active your account:
        <a href="{$url}" target="_blank">{$url}</a></br>
        (this link can be use in 24 hours)
EOF;

        $message->setBody($emailBody,'text/html','utf-8');
        try {
            $res1= $mailer->send($message);
            if($res && $res1){
                odbc_commit(db_con());
                odbc_autocommit(db_con(),true);
                alertMes("register successfully, please active the account for using",'index.php');

            }

        } catch (Swift_TransportException $e) {
            die('Email Server Error:').$e->getMessage();
        }

        break;
    case 'login':
        echo "Login Successfully!";
        break;
}