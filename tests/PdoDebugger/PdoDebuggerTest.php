<?php

class PdoDebuggerTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testQuestionMarks()
    {
        $sql = 'INSERT INTO users(login, password, email) VALUES (?, ?, ?)';
        $params = array(
            'jdoe',
            'p4$$w0rd',
            'john.doe@example.com',
        );
        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, 'INSERT INTO users(login, password, email) VALUES (\'jdoe\', \'p4$$w0rd\', \'john.doe@example.com\')');
    }

    public function testMarkers()
    {
        $sql = 'INSERT INTO users(login, password, email) VALUES (:login, :password, :email)';
        $params = array(
            'login' => 'jdoe',
            'password' => 'p4$$w0rd',
            'email' => 'john.doe@example.com',
        );
        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, 'INSERT INTO users(login, password, email) VALUES (\'jdoe\', \'p4$$w0rd\', \'john.doe@example.com\')');
    }
}
