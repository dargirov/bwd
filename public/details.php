<?php
session_start();

include_once '../private/config.php';

$pdo = new PDO('sqlite:../private/bgwebdir.db');

$acronym = filter_input(INPUT_GET, 'n');
if (strlen($acronym) < 3 || strlen($acronym) > 500)
{
    header('Location: /');
    exit;
}

$website_q = $pdo->prepare("SELECT * FROM Websites WHERE Acronym = :acronym");
$website_q->execute([':acronym' => $acronym]);
$website = $website_q->fetch();
if ($website === false || !is_array($website))
{
    header('Location: /');
    exit;
}

$category_q = $pdo->prepare("SELECT * FROM Categories WHERE Id = :id");
$category_q->execute([':id' => $website['CategoryId']]);
$category = $category_q->fetch();

$page_title = htmlspecialchars($website['Title']);

include_once '../private/header.php';
?>
<main>
    <div>
        <div id="main-left" class="details-page">
            <h1><?php echo htmlspecialchars($website['Title']); ?></h1>
            <div><?php echo htmlspecialchars($website['Description']); ?></div>
            <?php
            if (mb_strlen($website['FullDescription']) > 0)
            {
            ?>
                <div id="details-full-info"><?php echo htmlspecialchars($website['FullDescription']); ?></div>
            <?php
            }
            ?>
            <div>Категория: <a href="/category.php?n=<?php echo $category['Acronym']; ?>"><?php echo $category['Name']; ?></a></div>
        </div>
        <div id="main-right">
            <div>
                <ul id="details-contacts">
                    <?php
                    if (mb_strlen($website['CityId']) > 0)
                    {
                        $city_q = $pdo->prepare("SELECT * FROM Cities WHERE Id = :id");
                        $city_q->execute([':id' => $website['CityId']]);
                        $city = $city_q->fetch();
                        ?>
                        <li><img src="/images/city.svg"> <?php echo $city['Type'] === '1' ? 'гр.' : 'с.'; ?>&nbsp;<i class="city"><?php echo mb_strtolower($city['Name']); ?></i></li>
                        <?php
                    }

                    if (mb_strlen($website['Address']) > 0)
                    {
                        ?>
                        <li><img src="/images/address.svg"> <?php echo htmlspecialchars($website['Address']); ?></li>
                        <?php
                    }

                    if (mb_strlen($website['Phone']) > 0)
                    {
                        ?>
                        <li><img src="/images/phone.svg"> <?php echo htmlspecialchars($website['Phone']); ?></li>
                        <?php
                    }

                    if (mb_strlen($website['Email']) > 0)
                    {
                        ?>
                        <li><img src="/images/email.svg"> <?php echo htmlspecialchars($website['Email']); ?></li>
                        <?php
                    }

                    if (mb_strlen($website['Url']) > 0)
                    {
                        ?>
                        <li><img src="/images/www.svg"> <a href="<?php echo $website['Url']; ?>" target="_blank"><?php echo $website['Url']; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</main>
<?php
include_once '../private/footer.php';
?>