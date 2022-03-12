<?php

$categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC");
$cities = $pdo->query("SELECT * FROM cities WHERE Type = 1 ORDER BY Name ASC");

$submit = filter_input(INPUT_POST, 'submit', FILTER_VALIDATE_INT);
$title = trim(filter_input(INPUT_POST, 'title'));
$category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
$description = trim(filter_input(INPUT_POST, 'description'));
$full_description = trim(filter_input(INPUT_POST, 'full_description'));
$url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
$city = filter_input(INPUT_POST, 'city', FILTER_VALIDATE_INT);
$address = filter_input(INPUT_POST, 'address');
$phone = filter_input(INPUT_POST, 'phone');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$recaptcha = filter_input(INPUT_POST, 'g-recaptcha-response');
if ($url !== false)
{
    $url = trim($url);
}

$error_title = '';
$error_category = '';
$error_description = '';
$error_full_description = '';
$error_url = '';
$error_city = '';
$error_address = '';
$error_phone = '';
$error_email = '';
$has_error = false;
$success = false;

$user_logged_in = array_key_exists('loggedin', $_SESSION) && $_SESSION['loggedin'] === 1;

if ($submit === 1 && $user_logged_in)
{
    if (mb_strlen($title) < 5)
    {
        $error_title = 'Въведеното заглавие трябва да съдържа поне 5 символа';
        $has_error = true;
    }

    if (mb_strlen($title) > 255)
    {
        $error_title = 'Въведеното заглавие не трябва да съдържа повече от 255 символа';
        $has_error = true;
    }

    if ($category === false)
    {
        $error_category = 'Избери валидна категория';
        $has_error = true;
    }

    if (mb_strlen($error_category) === 0)
    {
        $categoryExists = $pdo->prepare("SELECT COUNT(*) AS n FROM Categories WHERE Id = :id");
        $categoryExists->execute([':id' => $category]);
        if ($categoryExists->fetch()['n'] != 1)
        {
            $error_category = 'Избери валидна категория';
            $has_error = true;
        }
    }

    if (mb_strlen($description) < 20)
    {
        $error_description = 'Въведеното кратко описание трябва да съдържа поне 20 символа';
        $has_error = true;
    }

    if (mb_strlen($description) > 500)
    {
        $error_description = 'Въведеното кратко описание не трябва да съдържа повече от 500 символа';
        $has_error = true;
    }

    if (mb_strlen($full_description) > 5000)
    {
        $error_description = 'Въведеното пълно описание не трябва да съдържа повече от 5000 символа';
        $has_error = true;
    }

    if (mb_strlen($url) < 5)
    {
        $error_url = 'Въведеният url адрес трябва да съдържа поне 5 символа';
        $has_error = true;
    }

    if (mb_strlen($url) > 100)
    {
        $error_url = 'Въведеният url адрес не трябва да съдържа повече от 100 символа';
        $has_error = true;
    }

    if ($url === false)
    {
        $error_url = 'Въведеният url адрес не е валиден';
        $has_error = true;
    }

    if (mb_strlen($error_url) === 0)
    {
        $urlExists = $pdo->prepare("SELECT COUNT(*) AS n FROM Websites WHERE Url = :url");
        $urlExists->execute([':url' => $url]);
        if ($urlExists->fetch()['n'] != 0)
        {
            $error_url = 'Въведеният url адрес е вече добавен в системата';
            $has_error = true;
        }
    }

    if (mb_strlen($error_url) === 0 && substr($url, 0, 7) !== 'http://' && substr($url, 0, 8) !== 'https://')
    {
        $error_url = 'Въведеният url адрес трябва да започва с http:// или https://';
        $has_error = true;
    }

    if ($city === 0)
    {
        $city = null;
    }

    if ($city > 0)
    {
        $cityExists = $pdo->prepare("SELECT COUNT(*) AS n FROM Cities WHERE Id = :id");
        $cityExists->execute([':id' => $city]);
        if ($cityExists->fetch()['n'] != 1)
        {
            $error_city = 'Избери валиден град';
            $has_error = true;
        }
    }

    if (mb_strlen($address) > 200)
    {
        $error_address = 'Въведеният адрес не трябва да съдържа повече от 200 символа';
        $has_error = true;
    }

    if (mb_strlen($phone) > 20)
    {
        $error_phone = 'Въведеният телефон не трябва да съдържа повече от 20 символа';
        $has_error = true;
    }

    if ($email !== false && mb_strlen($email) < 5)
    {
        $error_email = 'Въведеният email адрес трябва да съдържа поне 5 символа';
        $has_error = true;
    }

    if ($email !== false && mb_strlen($email) > 50)
    {
        $error_email = 'Въведеният email адрес не трябва да съдържа повече от 50 символа';
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
            $random_acronym = 'a'. microtime(true);

            $insert = $pdo->prepare("INSERT INTO Websites
                       (Title, CategoryId, Description, FullDescription, Url, DateCreated, Active, Address, Phone, Email, CityId, UserId, Acronym)
                VALUES (:title, :category, :description, :full_description, :url, datetime('now'), 0, :address, :phone, :email, :city, :user, :acronym)");
            $insert->bindParam(':title', $title);
            $insert->bindParam(':category', $category);
            $insert->bindParam(':description', $description);
            $insert->bindParam(':full_description', $full_description);
            $insert->bindParam(':url', $url);
            $insert->bindParam(':address', $address);
            $insert->bindParam(':phone', $phone);
            $insert->bindParam(':email', $email);
            $insert->bindParam(':city', $city);
            $insert->bindParam(':user', $_SESSION['loggedin_user_id']);
            $insert->bindParam(':acronym', $random_acronym);
            $success = $insert->execute();
            if (!$success)
            {
                var_dump($pdo->errorInfo(), $pdo->errorCode());
            }
        }
    }
}

?>
<main class="main-form">
    <div>
        <div id="main-left">
            <strong>Добави сайт</strong>
            <?php
            if ($success)
            {
            ?>
                Информацията е добавена успешно. Имайте предвид, че всяка страница се валидира ръчно, което може да отнеме няколко работни дни.
            <?php
            }
            else if (!$user_logged_in)
            {
            ?>
                За да добавите информация трябва да се <a href="#" class="register-popup">регистрирате</a> или да <a href="#" class="login-popup">влезете</a> в профила си.
                <br>Създаването на нов профил е безплатно, отнема под минута и ще ви позволи да добавяте и редактирате сайтове.
            <?php
            }
            else
            {
            ?>
            Полетата означени със * са задължителни
            <form method="post" action="/add">
                <table>
                    <tr>
                        <td>Заглавие *</td>
                        <td>
                            <input type="text" name="title" maxlength="255" value="<?php echo htmlspecialchars($title); ?>">
                            <?php
                            if (mb_strlen($error_title) > 0)
                            {
                                echo '<span class="error">' . $error_title . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Категория *</td>
                        <td>
                            <select name="category">
                                <?php
                                foreach($categories as $c)
                                {
                                    echo '<option value="' . $c['Id'] . '">' . $c['Name'] . '</option>';
                                }
                                ?>
                            </select>
                            <?php
                            if (mb_strlen($error_category) > 0)
                            {
                                echo '<span class="error">' . $error_category . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Кратко описание *</td>
                        <td>
                            <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
                            <?php
                            if (mb_strlen($error_description) > 0)
                            {
                                echo '<span class="error">' . $error_description . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Пълно описание</td>
                        <td>
                            <textarea name="full_description"><?php echo htmlspecialchars($full_description); ?></textarea>
                            <?php
                            if (mb_strlen($error_full_description) > 0)
                            {
                                echo '<span class="error">' . $error_full_description . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Url адрес *</td>
                        <td>
                            <input type="text" name="url" maxlength="100" value="<?php echo htmlspecialchars($url); ?>">
                            <?php
                            if (mb_strlen($error_url) > 0)
                            {
                                echo '<span class="error">' . $error_url . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Град</td>
                        <td>
                            <select name="city">
                                <option value="">----</option>
                                <?php
                                foreach($cities as $c)
                                {
                                    echo '<option value="' . $c['Id'] . '">' . $c['Name'] . ' (' . $c['PostCode'] . ')</option>';
                                }
                                ?>
                            </select>
                            <?php
                            if (mb_strlen($error_city) > 0)
                            {
                                echo '<span class="error">' . $error_city . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Адрес</td>
                        <td>
                            <input type="text" name="address" maxlength="200" value="<?php echo htmlspecialchars($address); ?>">
                            <?php
                            if (mb_strlen($error_address) > 0)
                            {
                                echo '<span class="error">' . $error_address . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Телефон</td>
                        <td>
                            <input type="text" name="phone" maxlength="20" value="<?php echo htmlspecialchars($phone); ?>">
                            <?php
                            if (mb_strlen($error_phone) > 0)
                            {
                                echo '<span class="error">' . $error_phone . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Email адрес</td>
                        <td>
                            <input type="text" name="email" maxlength="50" value="<?php echo htmlspecialchars($email); ?>">
                            <?php
                            if (mb_strlen($error_email) > 0)
                            {
                                echo '<span class="error">' . $error_email . '</span>';
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
                            <input type="submit" value="Добави">
                            <input type="hidden" name="submit" value="1">
                        </td>
                    </tr>
                </table>
            </form>
            <?php
            }
            ?>
        </div>
    </div>
</main>