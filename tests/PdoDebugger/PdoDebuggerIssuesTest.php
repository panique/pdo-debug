<?php

class PdoDebuggerIssuesTest extends PHPUnit_Framework_TestCase
{
    public function testIssue4()
    {
        $sql = <<<eof
SELECT
    u.id AS uid,
    t.id AS tid,
    t.lastmodifieddate AS tlmd,
    ut.lastmodifieddate AS utlmd
FROM `territory` AS t
JOIN `userterritory` AS ut ON (ut.territoryid = t.id)
JOIN `user` AS u ON (ut.userid = u.id)
WHERE u.lastmodifieddate > (SELECT lastrun FROM `config` WHERE username = :username)
OR ut.lastmodifieddate > (SELECT lastrun FROM `config` WHERE username = :username)
OR t.lastmodifieddate > (SELECT lastrun FROM `config` WHERE username = :username)
eof;

        $expected = <<<eof
SELECT
    u.id AS uid,
    t.id AS tid,
    t.lastmodifieddate AS tlmd,
    ut.lastmodifieddate AS utlmd
FROM `territory` AS t
JOIN `userterritory` AS ut ON (ut.territoryid = t.id)
JOIN `user` AS u ON (ut.userid = u.id)
WHERE u.lastmodifieddate > (SELECT lastrun FROM `config` WHERE username = 'johndoe')
OR ut.lastmodifieddate > (SELECT lastrun FROM `config` WHERE username = 'johndoe')
OR t.lastmodifieddate > (SELECT lastrun FROM `config` WHERE username = 'johndoe')
eof;

        $params = array(
            'username' => 'johndoe',
        );

        $res = PdoDebugger::show($sql, $params);
        $this->assertEquals($res, $expected);
    }
}
