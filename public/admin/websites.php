<?php

session_start();
$page_title = 'Web сайтове';
$active_page_websites = true;

if (!array_key_exists('admin_loggedin', $_SESSION) || $_SESSION['admin_loggedin'] !== 1)
{
    header('Location: /');
    exit;
}

$pdo = new PDO('sqlite:../../private/db/bgwebdir.db');

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id > 0)
{
    $websites_query = $pdo->prepare("SELECT * FROM Websites WHERE Id = :id");
    $websites_query->bindParam(':id', $id);
    $websites_query->execute();
    $website = $websites_query->fetch();

    $categories = $pdo->query("SELECT * FROM Categories ORDER BY Name ASC");
    $cities = $pdo->query("SELECT * FROM Cities ORDER BY Name ASC");
}

$action = filter_input(INPUT_POST, 'action');
if ($action === 'edit' && $id > 0)
{
    $title = filter_input(INPUT_POST, 'title');
    $acronym = filter_input(INPUT_POST, 'acronym');
    $category = filter_input(INPUT_POST, 'category', FILTER_VALIDATE_INT);
    $description = filter_input(INPUT_POST, 'description');
    $full_description = filter_input(INPUT_POST, 'full_description');
    $search_string = filter_input(INPUT_POST, 'search_string');
    $url = filter_input(INPUT_POST, 'url');
    $city = filter_input(INPUT_POST, 'city', FILTER_VALIDATE_INT);
    $address = filter_input(INPUT_POST, 'address');
    $phone = filter_input(INPUT_POST, 'phone');
    $email = filter_input(INPUT_POST, 'email');

    $update_query = $pdo->prepare("UPDATE Websites SET
        Title = :title,
        Acronym = :acronym,
        CategoryId = :category,
        Description = :description,
        FullDescription = :full_description,
        SearchString = :search_string,
        Url = :url,
        CityId = :city,
        Address = :address,
        Phone = :phone,
        Email = :email
        Where Id = :id");
    $update_query->bindParam(':title', $title);
    $update_query->bindParam(':acronym', $acronym);
    $update_query->bindParam(':category', $category);
    $update_query->bindParam(':description', $description);
    $update_query->bindParam(':full_description', $full_description);
    $update_query->bindParam(':search_string', $search_string);
    $update_query->bindParam(':url', $url);
    $update_query->bindParam(':city', $city);
    $update_query->bindParam(':address', $address);
    $update_query->bindParam(':phone', $phone);
    $update_query->bindParam(':email', $email);
    $update_query->bindParam(':id', $id);
    $update_result = $update_query->execute();
    if (!$update_result)
    {
        var_dump($update_query->errorInfo());
        exit;
    }

    header('Location: /admin/websites.php?id=' . $id);
    exit;
}

$websites = $pdo->query("SELECT * FROM Websites ORDER BY Id DESC");

include_once '../../private/admin/header.php';
?>
<?php
if ($id > 0)
{
?>
    <div class="row">
        <div class="col">
            <form method="post" action="/admin/websites.php?id=<?php echo $id; ?>">
                <div class="row">
                    <div class="col col-sm-8 mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($website['Title']); ?>">
                    </div>
                    <div class="col mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" class="form-select" id="category">
                            <?php
                            foreach($categories as $c)
                            {
                                $selected = $c['Id'] === $website['CategoryId'] ? ' selected="selected"' : '';
                                echo '<option value="' . $c['Id'] . '" ' . $selected . '>' . $c['Name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col mb-3">
                        <label for="acronym" class="form-label">Acronym</label>
                        <input type="text" class="form-control" id="acronym" name="acronym" value="<?php echo $website['Acronym']; ?>">
                    </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" id="description" style="height: 100px;"><?php echo htmlspecialchars($website['Description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="full_description" class="form-label">Full Description</label>
                    <textarea name="full_description" class="form-control" id="full_description" style="height: 200px;"><?php echo htmlspecialchars($website['FullDescription']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="search_string" class="form-label">Search string</label>
                    <textarea name="search_string" class="form-control" id="search_string" style="height: 200px;"><?php echo htmlspecialchars(mb_strlen($website['SearchString']) === 0 ? str_replace([','], ' ', mb_strtolower($website['Title'] . ' ' . $website['Description'] . ' ' . $website['FullDescription'])) : $website['SearchString']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col col-sm-3 mb-3">
                        <label for="url" class="form-label">Url</label>
                        <input type="text" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($website['Url']); ?>">
                    </div>
                    <div class="col mb-3">
                        <label for="city" class="form-label">City</label>
                        <select name="city" class="form-select" id="city">
                            <option value="">----</option>
                            <?php
                            foreach($cities as $c)
                            {
                                $selected = $c['Id'] === $website['CityId'] ? ' selected="selected"' : '';
                                echo '<option value="' . $c['Id'] . '" ' . $selected . '>' . $c['Name'] . ' (' . $c['PostCode'] . ')</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($website['Address']); ?>">
                    </div>
                    <div class="col col-sm-2 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($website['Phone']); ?>">
                    </div>
                    <div class="col mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($website['Email']); ?>">
                    </div>
                </div>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <button type="submit" class="btn btn-primary">Запази</button>
            </form>
        </div>
    </div>
<?php
}
else
{
?>
<div class="row">
    <div class="col">
        <table class="table table-hover table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th scope="col">Заглавие</th>
                    <th scope="col">Кратко описание</th>
                    <th scope="col">Url</th>
                    <th scope="col">Дата</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($websites as $website)
                {
                ?>
                    <tr class="<?php echo $website['Active'] == 1 ? '' : 'table-warning'; ?>">
                        <td style="white-space: pre-wrap;"><?php echo $website['Title']; ?></td>
                        <td style="white-space: pre-wrap;"><?php echo $website['Description']; ?></td>
                        <td style="white-space: pre-wrap;"><?php echo $website['Url']; ?></td>
                        <td><?php echo $website['DateCreated']; ?></td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm">
                                <?php
                                if ($website['Active'] == 1)
                                {
                                ?>
                                <a href="/admin/home.php?deactivate=<?php echo $website['Id']; ?>" class="btn btn-light">Деактивирай</a>
                                <?php
                                }
                                else
                                {
                                ?>
                                <a href="/admin/home.php?activate=<?php echo $website['Id']; ?>" class="btn btn-warning">Активирай</a>
                                <?php
                                }
                                ?>
                                <a href="/admin/websites.php?id=<?php echo $website['Id']; ?>" class="btn btn-primary">Редактирай</a>
                                <button type="button" class="btn btn-warning">Изтрии</button>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php
}
include_once '../../private/admin/footer.php';
?>