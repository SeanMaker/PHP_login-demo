<?php
/**
 * db_sqlserver.php
 * Author: RoadToTheExpert
 */

/**
 * db_con
 *
 * ����SqlServer���ӡ�
 * ʹ��ODBC���ӷ�ʽ����Ҫ��΢���������sqlserver for php���������������
 * ע�������汾��x86,64λ���ͣ���php.ini ���� odbc ��չ��
 * sqlserver��php��ͬһ̨����ͬһ��ϵͳ���������ӳɹ���Linuxû�����ԡ�
 *
 * ִ�д˺������Լ�������Ƿ�װ�ɹ���
 * ʹ��ʱ����ز�����Ҫ����Ϊ��ʵ��ʹ�õ����ݿ��Ӧ�Ĳ�����
 */
function db_con()
{
    $server = 'MSI\SQLEXPRESS';
    $username = 'sa'; //���ݿ��û���
    $password = 'test';   //���ݿ�����
    $database = 'test';     //���ݿ�
    $con_url = "Driver={SQL Server};Server=$server;Database=$database";
    //define ...
    $con = odbc_connect($con_url, $username, $password, SQL_CUR_USE_ODBC);
    if ($con)
        return $con;
        return null;
}

/**
 * db_query
 * ִ��select��䣬���ض�ά����(�����ֶ�)���ο�test.php��
 */
function db_query($sql, $fieldcount)
{
    $con = db_con();
    if (is_null($con))
        return null;

        $rs = odbc_exec($con, $sql);

        if( $rs === false) {
            //echo 'sql error : ' . $sql;
            //exit;
        }

        $table = [];

        if( $rs === false || odbc_num_rows($rs) == 0 ) {
            return $table;
        }

        while (odbc_fetch_row($rs)) {
            $row = [];
            $n = 0;
            while( $n < $fieldcount ) {
                $row[] = odbc_result($rs, ++$n);
            }
            $table[] = $row;
        }

        if( count($table) > 0  ) {
            odbc_free_result($rs);
        }

        odbc_close($con);

        return $table;
}

/**
 * odbc_exec
 * ִ��insert��update��delete��䡣
 * ���ִ�в��ɹ�������һ�����ݿ������odbc_connect������
 */
function db_exec($sql)
{
    $con = db_con();
    echo $con;
    if (is_null($con))
        return null;
        $dat = odbc_exec($con, $sql);
        odbc_close($con);
        return $dat;
}
