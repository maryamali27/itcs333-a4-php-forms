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

// Form values (used to repopulate the form) and validation errors.
$values = ['name' => '', 'email' => '', 'message' => ''];
$errors = [];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $values['name']    = post_field('name');
    $values['email']   = post_field('email');
    $values['message'] = post_field('message');

    // Name: required, 2-60 characters.
    if (!validate_required($values['name'])) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($values['name'], 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters (too short or too long).';
    }

    // Email: required and valid format.
    if (!validate_required($values['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($values['email'])) {
        $errors['email'] = 'Please enter a valid email address (invalid format).';
    }

    // Message: required, 10-1000 characters.
    if (!validate_required($values['message'])) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($values['message'], 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters (too short or too long).';
    }

    // Valid submission -> Post/Redirect/Get.
    if ($errors === []) {
        header('Location: success.php', true, 303);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us</title>
  <style>
    body { font-family: system-ui, sans-serif; max-width: 32rem; margin: 2rem auto; padding: 0 1rem; }
    label { display: block; margin-top: 1rem; font-weight: 600; }
    input, textarea { display: block; width: 100%; padding: .5rem; margin-top: .25rem; box-sizing: border-box; font: inherit; }
    textarea { min-height: 8rem; }
    .error { color: #b00020; font-size: .9rem; margin: .25rem 0 0; }
    .has-error { border: 2px solid #b00020; }
    .summary { background: #fdecea; border: 1px solid #b00020; padding: .75rem; }
    button { margin-top: 1.25rem; padding: .6rem 1.5rem; font: inherit; cursor: pointer; }
  </style>
</head>
<body>
  <h1>Contact Us</h1>

  <?php if ($errors !== []): ?>
    <p class="summary" role="alert">Please fix the errors below and submit again.</p>
  <?php endif; ?>

  <!-- novalidate: let the server-side PHP validation handle every check -->
  <form method="post" action="index.php" novalidate>
    <label for="name">Name</label>
    <input type="text" id="name" name="name"
           value="<?= e($values['name']) ?>"
           class="<?= isset($errors['name']) ? 'has-error' : '' ?>">
    <?php if (isset($errors['name'])): ?>
      <p class="error"><?= e($errors['name']) ?></p>
    <?php endif; ?>

    <label for="email">Email</label>
    <input type="email" id="email" name="email"
           value="<?= e($values['email']) ?>"
           class="<?= isset($errors['email']) ? 'has-error' : '' ?>">
    <?php if (isset($errors['email'])): ?>
      <p class="error"><?= e($errors['email']) ?></p>
    <?php endif; ?>

    <label for="message">Message</label>
    <textarea id="message" name="message"
              class="<?= isset($errors['message']) ? 'has-error' : '' ?>"><?= e($values['message']) ?></textarea>
    <?php if (isset($errors['message'])): ?>
      <p class="error"><?= e($errors['message']) ?></p>
    <?php endif; ?>

    <button type="submit">Send</button>
  </form>
</body>
</html>
