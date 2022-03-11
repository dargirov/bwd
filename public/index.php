<?php
session_start();

include_once '../private/config.php';

$page_title = 'Списък с линкове към фирми в България';
$class_active_home = true;

$pdo = new PDO('sqlite:../private/bgwebdir.db');



// $row = 1;
// if (($handle = fopen("./speedy_sites.csv", "r")) !== FALSE) {
//     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//         $name = trim($data[3]);
//         $code = trim($data[4]);
//         $type = trim($data[6]) == 'гр.' ? 1 : 2;

//         $sql = "INSERT INTO Cities (Name, Type, PostCode) VALUES (?,?,?)";
//         $stmt= $pdo->prepare($sql);
//         $stmt->execute([$name, $type, $code]);
//     }
//     fclose($handle);
// }
// exit;












$websites = $pdo->query("SELECT * FROM Websites WHERE Active = 1 ORDER BY Id DESC LIMIT 20");

include_once '../private/header.php';
?>
<main>
    <div>
        <div id="main-left">
            <strong>Последно добавени</strong>
            <ul>
                <?php
                foreach ($websites as $website)
                {
                ?>
                    <li>
                        <a href="/details.php?n=<?php echo $website['Acronym']; ?>"><?php echo $website['Title']; ?> (<?php echo date('d.m.Y', strtotime($website['DateCreated'])); ?>)</a>
                        <span><?php echo $website['Description']; ?></span>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
        <div id="main-right">
            <!-- <div>A</div> -->
        </div>
    </div>
</main>
<section id="category-list">
    <div>
        <strong>Категории</strong>
        <ul>
            <?php
            $header_categories = $pdo->query("SELECT * FROM categories ORDER BY Name ASC");
            foreach($header_categories as $c)
            {
            ?>
                <li><a href="/category.php?n=<?php echo $c['Acronym']; ?>"><img src="images/folder.svg"> <?php echo $c['Name']; ?></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
</section>
<?php
include_once '../private/footer.php';
?>