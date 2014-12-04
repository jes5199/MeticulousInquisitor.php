<?php
namespace MeticulousInquiry;

use \PDO;
use \PedanticQuerent\Querent;
use \MeticulousInquiry\Expression\Literal;
use \MeticulousInquiry\Expression\RawExpression;
use \MeticulousInquiry\QueryBuilder\Clause\SelectColumnsClause\SelectColumn;

class SelectBuilderTest extends \PHPUnit_Framework_TestCase {
    protected static $pdo;
    protected $querent;

    public static function setUpBeforeClass() {
        self::$pdo = new PDO("sqlite:/tmp/querent.sqlite");
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS test (value integer)");
        self::$pdo->exec("DELETE FROM test");
        self::$pdo->exec("INSERT INTO test VALUES (1)");
        self::$pdo->exec("INSERT INTO test VALUES (2)");
    }

    public function setUp() {
        $this->querent = new Querent(self::$pdo);
    }

    function testSelectBuilder() {
        $literal = new Literal(1);
        $builder = new SelectBuilder();
        $builder->selectColumn(new SelectColumn($literal, "number"));
        $this->assertEquals("SELECT 1 as `number`", $builder->getSQL());

        $row = $this->querent->selectOne($builder);
        $this->assertEquals(["number" => 1], $row);
    }

    function testSelectBuilderSelectingRawExpression() {
        $expression = new RawExpression(":two", [":two" => 2]);
        $this->assertEquals([":two" => 2], $expression->getBindings());

        $builder = new SelectBuilder();
        $builder->selectColumn(new SelectColumn($expression, "number"));
        $this->assertEquals("SELECT :two as `number`", $builder->getSQL());
        $this->assertEquals([":two" => 2], $builder->getBindings());

        $row = $this->querent->selectOne($builder);
        $this->assertEquals(["number" => 2], $row);
    }
}
