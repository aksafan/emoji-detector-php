Emoji Detection
===============

This library will find all emoji in an input string and return information about each emoji character. It supports emoji with skin tone modifiers, as well as the composite emoji that are made up of multiple people.

[![Build Status](https://travis-ci.org/aaronpk/emoji-detector-php.svg?branch=master)](https://travis-ci.org/aaronpk/emoji-detector-php)
_______________

N.B. This is fork of original [library](https://github.com/aaronpk/emoji-detector-php), made to update emoji map.
------------
Be aware, that this release (1.0.0) breaks backward compatibility:
1. using of all simple function was changed with methods from service object Emoji\Emogi;
2. output format is the same as it was before;
3. increased minimum PHP version to 7.1.

##### Before release:

```php
$input = 'Hello 👍🏼 World 👨‍👩‍👦‍👦';
$emoji = Emoji\detect_emoji($input);

print_r($emoji);
```
##### After release:

```php
$input = 'Hello 👍🏼 World 👨‍👩‍👦‍👦';
$emojiService = new \Emoji\Emoji();
$emoji = $emojiService->detectEmoji($input);
//OR
$emoji = (new \Emoji\Emoji())->detectEmoji('Hello 👍🏼 World 👨‍👩‍👦‍👦');

print_r($emoji);
```

Same goes for detecting a single emoji:

##### Before release:

```php
$input = ('👨‍👩‍👦‍👦');
$emoji = Emoji\isSingleEmoji($input);

print_r($emoji);
```
##### After release:

```php
$input = ('👨‍👩‍👦‍👦');
$emojiService = new \Emoji\Emoji();
$emoji = $emojiService->isSingleEmoji($input);
//OR
$emoji = (new \Emoji\Emoji())->isSingleEmoji(('👨‍👩‍👦‍👦'));

print_r($emoji);
```

Installation
------------

```
composer require p3k/emoji-detector
```

Or include `src/Emoji.php` in your project, and make sure the `map.json` and `regexp.json` files are available in the same folder as `Emoji.php`. You don't need any of the other files for use in your own projects.

Usage (deprecated one!)
-----

### Detect Emoji

```php
$input = "Hello 👍🏼 World 👨‍👩‍👦‍👦";
$emoji = Emoji\detect_emoji($input);

print_r($emoji);
```

The function returns an array with details about each emoji found in the string.

```
Array
(
  [0] => Array
    (
      [emoji] => 👨‍👩‍👦‍👦
      [short_name] => man-woman-boy-boy
      [num_points] => 7
      [points_hex] => Array
        (
          [0] => 1F468
          [1] => 200D
          [2] => 1F469
          [3] => 200D
          [4] => 1F466
          [5] => 200D
          [6] => 1F466
        )
      [hex_str] => 1F468-200D-1F469-200D-1F466-200D-1F466
      [skin_tone] =>
    )
  [1] => Array
    (
      [emoji] => 👍🏼
      [short_name] => +1
      [num_points] => 2
      [points_hex] => Array
        (
          [0] => 1F44D
          [1] => 1F3FC
        )

      [hex_str] => 1F44D-1F3FC
      [skin_tone] => skin-tone-3
    )
)
```

* `emoji` - The emoji sequence found, as the original byte sequence. You can output this to show the original emoji.
* `short_name` - The short name of the emoji, as defined by [Slack's emoji data](https://github.com/iamcal/emoji-data).
* `num_points` - The number of unicode code points that this emoji is composed of.
* `points_hex` - An array of each unicode code point that makes up this emoji. These are returned as hex strings. This will also include "invisible" characters such as the ZWJ character and skin tone modifiers.
* `hex_str` - A list of all unicode code points in their hex form separated by hyphens. This string is present in the [Slack emoji data](https://github.com/iamcal/emoji-data) array.
* `skin_tone` - If a skin tone modifier was used in the emoji, this field indicates which skin tone, since the `short_name` will not include the skin tone.


### Test if a string is a single emoji

Since simply counting the number of unicode characters in a string does not tell you how many visible emoji are in the string, determining whether a single character is an emoji is more involved. This function will return the emoji data only if the string contains a single emoji character, and false otherwise.

```php
$emoji = Emoji\isSingleEmoji('👨‍👩‍👦‍👦');
print_r($emoji);
```

```
Array
(
    [emoji] => 👨‍👩‍👦‍👦
    [short_name] => man-woman-boy-boy
    [num_points] => 7
    [points_hex] => Array
        (
            [0] => 1F468
            [1] => 200D
            [2] => 1F469
            [3] => 200D
            [4] => 1F466
            [5] => 200D
            [6] => 1F466
        )

    [hex_str] => 1F468-200D-1F469-200D-1F466-200D-1F466
    [skin_tone] =>
)
```

```php
$emoji = Emoji\isSingleEmoji('😻🐈');
// false
```


License
-------

Copyright 2017 by Aaron Parecki.

Available under the MIT license.

Emoji data sourced from [iamcal/emoji-data](https://github.com/iamcal/emoji-data) under the MIT license.

Emoji parsing regex sourced from [EmojiOne](https://github.com/Ranks/emojione) under the MIT license.

