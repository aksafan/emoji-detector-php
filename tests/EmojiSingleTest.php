<?php

use Emoji\Emoji;
use PHPUnit\Framework\TestCase;

class EmojiSingleTest extends TestCase
{
    /** @var Emoji */
    public $emojiService;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->emojiService = new Emoji();

        parent::__construct($name, $data, $dataName);
    }

    public function testSingleEmoji()
    {
        $string = '😻';
        $emoji = $this->emojiService->isSingleEmoji($string);
        $this->assertSame($string, $emoji['emoji']);
    }

    public function testSingleCompositeEmoji()
    {
        $string = '👨‍👩‍👦‍👦';
        $emoji = $this->emojiService->isSingleEmoji($string);
        $this->assertSame($string, $emoji['emoji']);
    }

    public function testMultipleEmoji()
    {
        $string = '😻🐈';
        $emoji = $this->emojiService->isSingleEmoji($string);
        $this->assertFalse($emoji);
    }

    public function testSingleEmojiWithText()
    {
        $string = 'kitty 😻';
        $emoji = $this->emojiService->isSingleEmoji($string);
        $this->assertFalse($emoji);
    }
}
