<?php

session_start();

if (!array_key_exists('admin_loggedin', $_SESSION) || $_SESSION['admin_loggedin'] !== 1)
{
    header('Location: /');
    exit;
}

$pdo = new PDO('sqlite:../../private/bgwebdir.db');

$activate = filter_input(INPUT_GET, 'activate', FILTER_VALIDATE_INT);
if ($activate !== false && $activate !== null && $activate > 0)
{
    $activate_query = $pdo->prepare("UPDATE Websites SET Active = 1 WHERE Id = :id");
    $activate_query->bindParam(':id', $activate);
    $activate_query->execute();
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

include_once '../../private/admin/header.php';
?>
<div class="row">
    <div class="col">
        <table class="table table-hover table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Url</th>
                    <th scope="col">Date</th>
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
                        <td style="white-space: pre-wrap;"><?php echo $website['Description']; ?></td>
                        <td style="white-space: pre-wrap;"><?php echo $website['Url']; ?></td>
                        <td><?php echo $website['DateCreated']; ?></td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm">
                                <a href="/admin/home.php?activate=<?php echo $website['Id']; ?>" class="btn btn-primary">Активирай</a>
                                <button type="button" class="btn btn-primary">Редактирай</button>
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
<div class="row">
    <div class="col">
        <form method="post" action="/admin/home.php">
            <input type="text" name="category_name" placeholder="Име" style="width: 300px; ">
            <input type="text" name="category_acronym" placeholder="Акроним" style="width: 300px; ">
            <input type="submit" value="Добави">
            <input type="hidden" name="add_category" value="1">
        </form>
        <ul>
            <?php
            foreach ($categories as $category)
            {
            ?>
                <li>
                    <?php echo $category['Id']; ?> <?php echo $category['Name']; ?> / <?php echo $category['Acronym']; ?>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>
<?php
include_once '../../private/admin/footer.php';
?>