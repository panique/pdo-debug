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

    public function testEscapeQuotes()
    {
        $sql = 'INSERT INTO users(login, password, email) VALUES (:login, :password, :email)';
        $params = array(
            'login' => 'jdoe',
            'password' => 'p4$\'$w0rd',
            'email' => 'john.doe@example.com',
        );
        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, 'INSERT INTO users(login, password, email) VALUES (\'jdoe\', \'p4$\\\'$w0rd\', \'john.doe@example.com\')');
    }

    public function testColumnsInParams()
    {
        $sql = 'INSERT INTO users(login, password, email) VALUES (:login, :password, :email)';
        $params = array(
            ':login' => 'jdoe',
            'password' => 'p4$$w0rd',
            'email' => 'john.doe@example.com',
        );
        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, 'INSERT INTO users(login, password, email) VALUES (\'jdoe\', \'p4$$w0rd\', \'john.doe@example.com\')');
    }

    public function testMultipleOccurrences()
    {
        $sql = 'SELECT * FROM users WHERE username LIKE :user OR username LIKE :username OR email LIKE :user';
        $params = array(
            'user' => '%jdoe%',
            'username' => '%j.doe%',
        );
        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, 'SELECT * FROM users WHERE username LIKE \'%jdoe%\' OR username LIKE \'%j.doe%\' OR email LIKE \'%jdoe%\'');
    }

    public function testNumbers()
    {
        $sql = 'SELECT * FROM test WHERE float_val > :float AND int_val > :int';
        $params = array(
            'float' => 3.14159,
            'int' => 42
        );
        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, 'SELECT * FROM test WHERE float_val > 3.14159 AND int_val > 42');
    }
}
