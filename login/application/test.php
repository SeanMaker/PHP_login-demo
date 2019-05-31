<?php
require_once 'db_sqlserver.php';

function test() {
    $sql = 'select *  from Client';
    $rs = db_query($sql, 8);


    foreach($rs as $r) {
        foreach($r as $c ) {
            echo $c."\t";
        }
        echo PHP_EOL;
    }

//     $sql = "insert into Client(name, account, age) values('myname', 'my_account', 18)";
//     $rs = db_exec($sql, 3);
//     var_dump($rs);
}

test();

