<?php
session_start();

include_once '../private/config.php';

$class_active_contacts = true;

$pdo = new PDO('sqlite:../private/bgwebdir.db');

$submit = filter_input(INPUT_POST, 'submit', FILTER_VALIDATE_INT);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$recaptcha = filter_input(INPUT_POST, 'g-recaptcha-response');
$content = trim(filter_input(INPUT_POST, 'content'));
$success = filter_input(INPUT_GET, 's', FILTER_VALIDATE_INT);

$error_email = '';
$error_content = '';
$has_error = false;

if ($submit === 1)
{
    if ($email !== false && strlen($email) < 5)
    {
        $error_email = 'Въведеният email адрес трябва да съдържа поне 5 символа';
        $has_error = true;
    }

    if ($email !== false && strlen($email) > 50)
    {
        $error_email = 'Въведеният email адрес не трябва да съдържа повече от 50 символа';
        $has_error = true;
    }

    if ($email === false)
    {
        $error_email = 'Въведеният email адрес е невалиден';
        $has_error = true;
    }

    if (strlen($content) < 5)
    {
        $error_content = 'Въведеното съобщение трябва да съдържа поне 5 символа';
        $has_error = true;
    }

    if (strlen($content) > 500)
    {
        $error_content = 'Въведеното съобщение не трябва да съдържа повече от 500 символа';
        $has_error = true;
    }

    if (!$has_error)
    {
        $postdata = http_build_query([
            'secret' => RECAPTCHA_SECRET_KEY,
            'response' => $recaptcha]);

        $opts = ['http' => [
            'method' => 'POST',
            'content' => $postdata]];

        $context = stream_context_create($opts);
        $recaptcha_result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $recaptcha_result_data = json_decode($recaptcha_result, true);
        if ($recaptcha_result_data['success'] === true)
        {
            $insert = $pdo->prepare("INSERT INTO Contacts
                (Email, Content, DateCreated)
                VALUES (:email, :content, datetime('now'))");
            $insert->bindParam(':email', $email);
            $insert->bindParam(':content', $content);
            $insert->execute();
            header('Location: /contacts.php?s=1');
        }
    }
}

include_once '../private/header.php';
?>
<main class="main-form">
    <div>
        <div id="main-left">
            <strong>За контакти</strong>
            <?php
            if ($success === 1)
            {
            ?>
                Съобщението е изпратено успешно. Ще ви отговорим възможно най-скоро.
            <?php
            }
            ?>
            <form method="post" action="/contacts.php">
                <table>
                    <tr>
                        <td>Email адрес *</td>
                        <td>
                            <input type="text" name="email" maxlength="50" value="<?php echo $email; ?>">
                            <?php
                            if (strlen($error_email) > 0)
                            {
                                echo '<span class="error">' . $error_email . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Съобщение *</td>
                        <td>
                            <textarea name="content"><?php echo $content; ?></textarea>
                            <?php
                            if (strlen($error_content) > 0)
                            {
                                echo '<span class="error">' . $error_content . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>" data-size="compact"></div></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="submit" value="Изпрати">
                            <input type="hidden" name="submit" value="1">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</main>
<?php
include_once '../private/footer.php';
?>