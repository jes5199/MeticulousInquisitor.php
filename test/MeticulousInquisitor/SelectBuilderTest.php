<?php
namespace MeticulousInquisitor;

use \PDO;
use \PedanticQuerent\Querent;
use \MeticulousInquisitor\Expression\Literal;
use \MeticulousInquisitor\Expression\RawExpression;
use \MeticulousInquisitor\Expression\Column;
use \MeticulousInquisitor\Clause\SelectColumnsClause\SelectColumn;
use \MeticulousInquisitor\Clause\SelectFromClause;
use \MeticulousInquisitor\SelectBuilder;

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
        $this->assertEquals("SELECT 1 AS `number`", $builder->getSQL());

        $row = $this->querent->selectOne($builder);
        $this->assertEquals(["number" => 1], $row);
    }

    function testSelectBuilderSelectingRawExpression() {
        $expression = new RawExpression(":two", [":two" => 2]);
        $this->assertEquals([":two" => 2], $expression->getBindings());

        $builder = new SelectBuilder();
        $builder->selectColumn(new SelectColumn($expression, "number"));
        $this->assertEquals("SELECT :two AS `number`", $builder->getSQL());
        $this->assertEquals([":two" => 2], $builder->getBindings());

        $row = $this->querent->selectOne($builder);
        $this->assertEquals(["number" => 2], $row);
    }

    function testSelectBuilderSelectExpressionAsName() {
        $expression = new RawExpression(":two", [":two" => 2]);
        $this->assertEquals([":two" => 2], $expression->getBindings());

        $builder = new SelectBuilder();
        $builder->selectExpression($expression)->asName("number");
        $this->assertEquals("SELECT :two AS `number`", $builder->getSQL());
        $this->assertEquals([":two" => 2], $builder->getBindings());

        $row = $this->querent->selectOne($builder);
        $this->assertEquals(["number" => 2], $row);
    }

    function testSelectBuilderSelectLiteralAsName() {
        $builder = new SelectBuilder();
        $builder->selectLiteral(1)->asName("one");
        $this->assertEquals("SELECT 1 AS `one`", $builder->getSQL());
        $this->assertEquals([], $builder->getBindings());

        $row = $this->querent->selectOne($builder);
        $this->assertEquals(["one" => 1], $row);
    }

    function testSelectBuilderSelectColumnNameAsName() {
        $builder = new SelectBuilder();
        $builder->selectColumnName('col')->asName("number");
        $this->assertEquals("SELECT `col` AS `number`", $builder->getSQL());
        $this->assertEquals([], $builder->getBindings());
    }

    function testSelectBuilderSelectColumnAsName() {
        $builder = new SelectBuilder();
        $builder->select('col')->asName("number");
        $this->assertEquals("SELECT `col` AS `number`", $builder->getSQL());
        $this->assertEquals([], $builder->getBindings());
    }

    function testSelectBuilderFromName() {
        $builder = new SelectBuilder();
        $builder->select("value")->from("test");

        $this->assertEquals("SELECT `value` FROM `test`", $builder->getSQL());
        $rows = $this->querent->selectSome($builder);
        $this->assertEquals([
            ["value" => 1],
            ["value" => 2],
        ], $rows);
    }

    function testSelectBuilderWithEverything() {
        $builder = new SelectBuilder();
        $builder->select("value")->select("name")->from("test")->where(new Literal("1"))->where("name = 1")
            ->groupBy("value")->having("value > 1")->orderBy("name")->orderBy("value", "DESC")->limit("2", "4");

        $placeholderNames = array_keys($builder->getBindings());
        $this->assertEquals(
            [
                // parameters from limit() are wrapped as placeholders for safety:
                $placeholderNames[0] => 2,
                $placeholderNames[1] => 4
            ],
            $builder->getBindings()
        );

        $this->assertEquals(
            "SELECT `value`, `name` FROM `test` WHERE 1 AND (name = 1) GROUP BY value HAVING value > 1"
            . " ORDER BY value DESC, name LIMIT {$placeholderNames[0]} OFFSET {$placeholderNames[1]}",
            $builder->getSQL()
        );
    }

    function testSelectBuilderWithSubquery() {
        $builder = (new SelectBuilder())->select(new Literal(1));
        $builder2 = (new SelectBuilder())->select($builder);
        $this->assertEquals(
            "SELECT (SELECT 1)",
            $builder2->getSQL()
        );
    }

    function testSelectBuilderFromSubquery() {
        $builder = (new SelectBuilder())->select(new Literal("1"))->asName("value");
        $builder2 = (new SelectBuilder())->select("subquery", "value")->from($builder, "subquery");
        $this->assertEquals(
            "SELECT `subquery`.`value` FROM (SELECT 1 AS `value`) subquery",
            $builder2->getSQL()
        );
    }

    function testSelectBuilderWhereEquals() {
        $builder = (new SelectBuilder())->select("name")->from("users")->whereEquals("id", 1);
        $placeholderName = array_keys($builder->getBindings())[0];
        $this->assertEquals(
            "SELECT `name` FROM `users` WHERE `id` = $placeholderName",
            $builder->getSQL()
        );
        $this->assertEquals(
            [$placeholderName => 1],
            $builder->getBindings()
        );
    }
}
