<?php

namespace Emoji;

use function array_key_exists;
use function count;
use function dechex;
use function file_get_contents;
use function implode;
use function json_decode;
use function mb_internal_encoding;
use function mb_strlen;
use function mb_substr;
use function ord;
use function preg_match_all;
use function str_replace;
use function strlen;
use function strtoupper;

class Emoji
{
    private const LONGEST_EMOJI = 8;

    /** @var array $map Array of emoji's data */
    private static $map = [];

    /** @var string $regexp Regexp to detect emoji */
    private static $regexp = [];

    /**
     * Emoji constructor.
     */
    public function __construct()
    {
        if (! self::$map) {
            self::$map = $this->loadMap();
        }
        if (! self::$regexp) {
            self::$regexp = $this->loadRegexp();
        }
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function detectEmoji(string $string): array
    {
        // Find all the emoji in the input string
        $previousEncoding = mb_internal_encoding();
        mb_internal_encoding('UTF-8');

        $data = [];
        if (preg_match_all(self::$regexp, $string, $matches)) {
            foreach ($matches[0] as $emojiDetected) {
                $points = [];
                $emojiDetectedLength = mb_strlen($emojiDetected);
                for ($i = 0; $i < $emojiDetectedLength; $i++) {
                    $points[] = strtoupper(dechex($this->uniOrd(mb_substr($emojiDetected, $i, 1))));
                }
                $hexString = implode('-', $points);

                $shortName = self::$map[$hexString] ?? null;
                $skinTone = null;
                $skinTones = [
                    '1F3FB' => 'skin-tone-2',
                    '1F3FC' => 'skin-tone-3',
                    '1F3FD' => 'skin-tone-4',
                    '1F3FE' => 'skin-tone-5',
                    '1F3FF' => 'skin-tone-6',
                ];
                foreach ($points as $point) {
                    if (array_key_exists($point, $skinTones)) {
                        $skinTone = $skinTones[$point];
                    }
                }

                $data[] = [
                    'emoji' => $emojiDetected,
                    'short_name' => $shortName,
                    'num_points' => mb_strlen($emojiDetected),
                    'points_hex' => $points,
                    'hex_str' => $hexString,
                    'skin_tone' => $skinTone,
                ];
            }
        }

        if ($previousEncoding) {
            mb_internal_encoding($previousEncoding);
        }

        return $data;
    }

    public function isSingleEmoji(string $string)
    {
        $previousEncoding = mb_internal_encoding();
        mb_internal_encoding('UTF-8');

        // If the string is longer than the longest emoji, it's not a single emoji
        if (mb_strlen($string) >= self::LONGEST_EMOJI) {
            return false;
        }

        $allEmoji = $this->detectEmoji($string);

        $emoji = false;

        // If there are more than one or none, return false immediately
        if (count($allEmoji) === 1) {
            $emoji = $allEmoji[0];
            // Check if there are any other characters in the string
            // Remove the emoji found
            $string = str_replace($emoji['emoji'], '', $string);

            // If there are any characters left, then the string is not a single emoji
            if (strlen($string) > 0) {
                $emoji = false;
            }
        }

        if ($previousEncoding) {
            mb_internal_encoding($previousEncoding);
        }

        return $emoji;
    }

    /**
     * Returns array of emoji's data
     *
     * @return array
     */
    private function loadMap(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/map.json'), true);
    }

    /**
     * Returns reg exp to detect emoji
     *
     * @return string
     */
    private function loadRegexp(): string
    {
        return '/(?:' . json_decode(file_get_contents(__DIR__ . '/regexp.json')) . ')/u';
    }

    /**
     * @param string $data
     *
     * @return int|null
     */
    private function uniOrd(string $data): ?int
    {
        $ord0 = ord($data[0]);
        if ($ord0 >= 0 && $ord0 <= 127) {
            return $ord0;
        }
        $ord1 = ord($data[1]);
        if ($ord0 >= 192 && $ord0 <= 223) {
            return ($ord0 - 192) * 64 + ($ord1 - 128);
        }
        $ord2 = ord($data[2]);
        if ($ord0 >= 224 && $ord0 <= 239) {
            return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
        }
        $ord3 = ord($data[3]);
        if ($ord0 >= 240 && $ord0 <= 247) {
            return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2 - 128) * 64 + ($ord3 - 128);
        }

        return null;
    }
}
