<?php
declare(strict_types=1);

/**
 * ITCS333 A4 — contact form.
 *   - GET: render the empty form
 *   - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *   - invalid POST: re-render with inline errors + repopulated fields (200)
 *   - valid POST: redirect (303) to the success page (Post/Redirect/Get)
 *   - all output is escaped with htmlspecialchars
 */

error_reporting(E_ALL);

require_once __DIR__ . '/../src/validate.php';

/**
 * Escape a value for safe output inside HTML text or attribute values.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Read a POST field as a trimmed string ('' if missing or not a string).
 */
function post_field(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

// Form values (used to repopulate the form).
$values = ['name' => '', 'email' => '', 'message' => ''];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $values['name']    = post_field('name');
    $values['email']   = post_field('email');
    $values['message'] = post_field('message');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
  <form method="post" action="index.php">
    <label>Name <input type="text" name="name" value="<?= e($values['name']) ?>"></label><br>
    <label>Email <input type="email" name="email" value="<?= e($values['email']) ?>"></label><br>
    <label>Message <textarea name="message"><?= e($values['message']) ?></textarea></label><br>
    <button type="submit">Send</button>
  </form>
</body>
</html>
