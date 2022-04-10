<?php

session_start();
$page_title = 'Начало';
$active_page_home = true;

if (!array_key_exists('admin_loggedin', $_SESSION) || $_SESSION['admin_loggedin'] !== 1)
{
    header('Location: /');
    exit;
}

$pdo = new PDO('sqlite:../../private/db/bgwebdir.db');

$activate = filter_input(INPUT_GET, 'activate', FILTER_VALIDATE_INT);
if ($activate !== false && $activate !== null && $activate > 0)
{
    $activate_query = $pdo->prepare("UPDATE Websites SET Active = 1 WHERE Id = :id");
    $activate_query->bindParam(':id', $activate);
    $activate_query->execute();
}

$contact_delete = filter_input(INPUT_GET, 'contact_delete', FILTER_VALIDATE_INT);
if ($contact_delete !== false && $contact_delete !== null && $contact_delete > 0)
{
    $contact_delete_query = $pdo->prepare("DELETE FROM Contacts WHERE Id = :id");
    $contact_delete_query->bindParam(':id', $contact_delete);
    $contact_delete_query->execute();
}

$contact_seen = filter_input(INPUT_GET, 'contact_seen', FILTER_VALIDATE_INT);
if ($contact_seen !== false && $contact_seen !== null && $contact_seen > 0)
{
    $contact_seen_query = $pdo->prepare("UPDATE Contacts SET Seen = 1 WHERE Id = :id");
    $contact_seen_query->bindParam(':id', $contact_seen);
    $contact_seen_query->execute();
}

$add_category = filter_input(INPUT_POST, 'add_category', FILTER_VALIDATE_INT);
$category_name = trim(filter_input(INPUT_POST, 'category_name'));
$category_acronym = trim(filter_input(INPUT_POST, 'category_acronym'));
if ($add_category === 1)
{
    // if (strlen($category_acronym) === 0)
    // {
    //     $category_acronym = mb_strtolower($category_name);
    //     $category_acronym = str_replace(' и ', '-', $category_acronym);
    //     $category_acronym = str_replace(' - ', '-', $category_acronym);
    //     $category_acronym = str_replace(',', '', $category_acronym);
    //     $category_acronym = str_replace(' ', '-', $category_acronym);
    // }

    $category_query = $pdo->prepare("INSERT INTO Categories (Name, Acronym) VALUES (:name, :acronym)");
    $category_query->bindParam(':name', $category_name);
    $category_query->bindParam(':acronym', $category_acronym);
    $category_query->execute();
    header('Location: /admin/home.php');
    exit;
}

$websites = $pdo->query("SELECT * FROM Websites WHERE Active = 0 ORDER BY Id DESC");
$categories = $pdo->query("SELECT * FROM Categories ORDER BY Name ASC");
$users = $pdo->query("SELECT Id, Email, DateCreated FROM Users ORDER BY Id DESC");
$contacts = $pdo->query("SELECT * FROM Contacts ORDER BY Id DESC");

include_once '../../private/admin/header.php';
?>
<div class="row">
    <div class="col">
        <div class="alert alert-warning" role="alert">Чакащи активиране</div>
        <table class="table table-hover table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th scope="col">Заглавие</th>
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
                    <tr>
                        <td style="white-space: pre-wrap;"><?php echo $website['Title']; ?></td>
                        <td style="white-space: pre-wrap;"><?php echo $website['Url']; ?></td>
                        <td><?php echo $website['DateCreated']; ?></td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm">
                                <a href="/admin/home.php?activate=<?php echo $website['Id']; ?>" class="btn btn-warning">Активирай</a>
                                <a href="/admin/websites.php?id=<?php echo $website['Id']; ?>" class="btn btn-primary">Редактирай</a>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="alert alert-secondary" role="alert">Потребители</div>
        <table class="table table-hover table-bordered table-sm mt-3">
            <thead class="table-light">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Email</th>
                    <th scope="col">Дата</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $user)
            {
            ?>
                <tr>
                    <td><?php echo $user['Id']; ?></td>
                    <td><?php echo $user['Email']; ?></td>
                    <td><?php echo $user['DateCreated']; ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        <div class="alert alert-secondary" role="alert">Коментари</div>
        <table class="table table-hover table-bordered table-sm mt-3">
            <thead class="table-light">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Email</th>
                    <th scope="col">Коментар</th>
                    <th scope="col">Дата</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($contacts as $contact)
            {
            ?>
                <tr class="<?php echo $contact['Seen'] == 0 ? 'table-warning' : ''; ?>">
                    <td><?php echo $contact['Id']; ?></td>
                    <td><?php echo $contact['Email']; ?></td>
                    <td><?php echo $contact['Content']; ?></td>
                    <td><?php echo $contact['DateCreated']; ?></td>
                    <td>
                        <div class="btn-group-vertical btn-group-sm" role="group">
                            <a href="/admin/home.php?contact_delete=<?php echo $contact['Id']; ?>" class="btn btn-outline-primary">Изтрии</a>
                            <a href="/admin/home.php?contact_seen=<?php echo $contact['Id']; ?>" class="btn btn-outline-primary">Видяно</a>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="col">
        <form method="post" action="/admin/home.php">
            <div class="row g-3">
                <div class="col col-md-5"><input type="text" class="form-control" name="category_name" placeholder="Име"></div>
                <div class="col col-md-5"><input type="text" class="form-control" name="category_acronym" placeholder="Акроним"></div>
                <div class="col col-md-2"><input type="submit" class="btn btn-primary" value="Добави"><input type="hidden" name="add_category" value="1"></div>
            </div>
        </form>
        <table class="table table-hover table-bordered table-sm mt-3">
            <thead class="table-light">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Име</th>
                    <th scope="col">Акроним</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($categories as $category)
            {
            ?>
                <tr>
                    <td><?php echo $category['Id']; ?></td>
                    <td><?php echo $category['Name']; ?></td>
                    <td><?php echo $category['Acronym']; ?></td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php
include_once '../../private/admin/footer.php';
?>