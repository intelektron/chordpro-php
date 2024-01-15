<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use ChordPro\Line\Comment;

final class CommentTest extends TestCase
{
    public function testClassAccess(): void
    {
        $comment = new Comment('Test Comment');
        $this->assertEquals('Test Comment', $comment->getContent());
    }
}
