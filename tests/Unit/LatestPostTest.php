<?php

declare(strict_types=1);

namespace Blog\Test\Unit;

use Blog\Database;
use Blog\LatestPosts;
use PDO;
use PDOStatement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LatestPostTest extends TestCase
{
    /**
     * @var LatestPosts
     */
    private LatestPosts $object;

    /**
     * @var MockObject|PDO
     */
    private MockObject $database;

    /**
     * @var MockObject|PDO
     */
    private MockObject $pdo;

    /**
     * @var MockObject|PDO
     */
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);

        $this->pdo = $this->createMock(PDO::class);

        $this->database->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->pdo);

        $this->pdoStatement = $this->createMock(PDOStatement::class);

        $this->object = new LatestPosts($this->database);
    }

    public function testGetEmpty(): void
    {
        $limit = 0;
        $expected = [];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute');

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expected);

        $result = $this->object->get($limit);

        $this->assertEmpty($result);
    }

    public function testGet(): void
    {
        $limit = 3;
        $expected = [
            [
                'title' => 'My eight post',
                'author' => 'Author'
            ]
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM post ORDER BY published_date DESC LIMIT :limit'))
            ->willReturn($this->pdoStatement);

        $this->pdoStatement->expects($this->once())
            ->method('execute');

        $this->pdoStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($expected);

        $this->pdoStatement->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':limit'), $this->equalTo($limit));

        $result = $this->object->get($limit);

        $this->assertNotEmpty($result);
    }
}
