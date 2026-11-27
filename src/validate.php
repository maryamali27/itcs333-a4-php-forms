<?php
declare(strict_types=1);

/**
 * ITCS333 A4 — pure validation functions.
 * They are unit-tested by the autograder (tests/UnitTest.php).
 */

/**
 * True when $v is not empty after trimming surrounding whitespace.
 */
function validate_required(string $v): bool
{
    return trim($v) !== '';
}

/**
 * True when $v is a syntactically valid email address.
 */
function validate_email(string $v): bool
{
    return filter_var($v, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_length(string $v, int $min, int $max): bool
{
    // TODO: return true when mb_strlen($v) is within [$min, $max]
    return false;
}
