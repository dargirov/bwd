<?php

$websites = $pdo->prepare("SELECT * FROM Websites WHERE UserId = :user ORDER BY Id DESC");
$websites->execute([':user' => $_SESSION['loggedin_user_id']]);

$categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC");
$cities = $pdo->query("SELECT * FROM cities WHERE Type = 1 ORDER BY Name ASC");

$edit_id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
$submit = filter_input(INPUT_POST, 'submit', FILTER_VALIDATE_INT);

if ($edit_id > 0)
{
    $website_q = $pdo->prepare("SELECT * FROM Websites WHERE Id = :id");
    $website_q->execute([':id' => $edit_id]);
    $website = $website_q->fetch();
    $title = $website['Title'];
    $category = $website['CategoryId'];
    $description = $website['Description'];
    $full_description = $website['FullDescription'];
    $url = $website['Url'];
    $city = $website['CityId'];
    $address = $website['Address'];
    $phone = $website['Phone'];
    $email = $website['Email'];
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
$error = false;

if ($submit === 1 && $edit_id > 0)
{
    $title = trim(filter_input(INPUT_POST, 'title'));
    $category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
    $description = trim(filter_input(INPUT_POST, 'description'));
    $full_description = trim(filter_input(INPUT_POST, 'full_description'));
    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $city = filter_input(INPUT_POST, 'city', FILTER_VALIDATE_INT);
    $address = filter_input(INPUT_POST, 'address');
    $phone = filter_input(INPUT_POST, 'phone');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if ($url !== false)
    {
        $url = trim($url);
    }

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
        $urlExists = $pdo->prepare("SELECT COUNT(*) AS n FROM Websites WHERE Url = :url AND Id != :id");
        $urlExists->execute([':url' => $url]);
        $urlExists->execute([':id' => $edit_id]);
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
        $insert = $pdo->prepare("UPDATE Websites SET
            Title = :title,
            CategoryId = :category,
            Description = :description,
            FullDescription = :full_description,
            Url = :url,
            Active = 0,
            Address = :address,
            Phone = :phone,
            Email = :email,
            CityId = :city
            WHERE Id = :id AND UserId = :user");
        $insert->bindParam(':title', $title);
        $insert->bindParam(':category', $category);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':full_description', $full_description);
        $insert->bindParam(':url', $url);
        $insert->bindParam(':address', $address);
        $insert->bindParam(':phone', $phone);
        $insert->bindParam(':email', $email);
        $insert->bindParam(':city', $city);
        $insert->bindParam(':id', $edit_id);
        $insert->bindParam(':user', $_SESSION['loggedin_user_id']);
        $success = $insert->execute();
        if (!$success)
        {
            $error = true;
        }
    }
}
?>
<main class="main-form">
    <div>
        <div id="main-left" class="user-profile">
            <strong>Профил</strong>
            <div><?php echo $_SESSION['loggedin_email']; ?></div>
            <?php
            if ($edit_id > 0)
            {
                if ($success)
                {
                ?>
                    Информацията е обновена успешно. Имайте предвид, че промяната се валидира ръчно, което може да отнеме няколко работни дни.
                <?php
                }
                else if ($error)
                {
                ?>
                    Възникна грешка при добавяне. Моля, опитайте по-късно или ни уведомете чрез формата за контакти.
                <?php
                }
                else
                {
                ?>
                <ul class="rules">
                    <li>Полетата означени със * са задължителни.</li>
                    <li>Използвайте кирилица за да въведете информацията.</li>
                    <li>Използването на html, javascript или друг вид код не е разрешено.</li>
                    <li>Забранено е добавяне на сайтове, които нарушават законите на Република България, имат порнографско или расистко съдържание.</li>
                    <li>Добавяйте само български сайтове.</li>
                    <li>Екипът на bgwebdir има правото да не одобри сайта, или да го премахне без предварително предупреждение.</li>
                </ul>
                <form method="post" action="/profile?edit=<?php echo $edit_id; ?>">
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
                                        $selected = $c['Id'] == $category ? ' selected="selected"' : '';
                                        echo '<option value="' . $c['Id'] . '"' . $selected . '>' . $c['Name'] . '</option>';
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
                                        $selected = $c['Id'] == $city ? ' selected="selected"' : '';
                                        echo '<option value="' . $c['Id'] . '"' . $selected . '>' . $c['Name'] . ' (' . $c['PostCode'] . ')</option>';
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
                            <td>
                                <input type="submit" value="Запази">
                                <input type="hidden" name="submit" value="1">
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
                }
            }
            else
            {
            ?>
                <strong>Добавени сайтове</strong>
                <table id="profile-edit-sites">
                <?php
                foreach ($websites as $website)
                {
                ?>
                    <tr>
                        <td>
                            <ul>
                                <li><?php echo $website['Title']; ?></li>
                                <li><img src="/images/www.svg" alt="уеб сайт"> <?php echo $website['Url']; ?></li>
                                <li><img src="/images/status-warning.svg" alt="уеб сайт"> <?php echo $website['Active'] == 1 ? 'Активен' : 'Чака одобрение'; ?></li>
                            </ul>
                        </td>
                        <td><a href="/profile?edit=<?php echo $website['Id']; ?>" class="btn">Редактирай</a></td>
                    </tr>
                <?php
                }
                ?>
                </table>
            <?php
            }
            ?>
        </div>
        <div id="main-right">
            <!-- <div>
            </div> -->
        </div>
    </div>
</main>