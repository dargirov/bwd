<?php
session_start();

include_once '../private/config.php';

$pdo = new PDO('sqlite:../private/bgwebdir.db');

$acronym = filter_input(INPUT_GET, 'n');
$category = $pdo->prepare("SELECT * FROM Categories WHERE Acronym = :acronym");
$category->execute([':acronym' => $acronym]);
$category_data = $category->fetch();
if ($category_data === false)
{
    header('Location: /');
    exit;
}

$websites = $pdo->prepare("SELECT * FROM Websites WHERE Active = 1 AND CategoryId = :category ORDER BY Id DESC");
$websites->execute([':category' => $category_data['Id']]);

include_once '../private/header.php';
?>
<main class="main-form">
    <div>
        <div id="main-left">
            <strong><?php echo $category_data['Name']; ?></strong>
            <ul>
                <?php
                foreach ($websites as $website)
                {
                ?>
                    <li>
                        <a href="/details.php?n=<?php echo $website['Acronym']; ?>"><?php echo $website['Title']; ?></a>
                        <span><?php echo $website['Description']; ?></span>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</main>
<?php
include_once '../private/footer.php';
?>