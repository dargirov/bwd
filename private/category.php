<?php

$p = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);
$current_page = 0;
if ($p !== null && $p !== false && is_int($p) && $p > 0 && $p < 10000)
{
    $current_page = (int)$p - 1;
}

$websites = $pdo->prepare("SELECT * FROM Websites WHERE Active = 1 AND CategoryId = :category ORDER BY Id DESC LIMIT :limit OFFSET :offset");
$websites->execute([':category' => $category_data['Id'], ':limit' => SITES_PER_PAGE, ':offset' => $current_page * SITES_PER_PAGE]);

$total_websites = $pdo->prepare("SELECT COUNT(*) AS n FROM Websites WHERE Active = 1 AND CategoryId = :category");
$total_websites->execute([':category' => $category_data['Id']]);
$total = $total_websites->fetch();

?>
<main class="main-form">
    <div>
        <div id="main-left">
            <strong>Категория <?php echo $category_data['Name']; ?></strong>
            <ul class="website-card">
                <?php
                foreach ($websites as $website)
                {
                ?>
                    <li>
                        <a href="/site/<?php echo $website['Acronym']; ?>"><?php echo $website['Title']; ?></a>
                        <span><?php echo $website['Description']; ?></span>
                    </li>
                <?php
                }
                ?>
            </ul>
            <ol class="paging">
            <?php
            for ($i = 1; $i <= ceil($total['n'] / SITES_PER_PAGE); $i++)
            {
                $selected = $current_page == ($i - 1) ? 'selected' : '';
            ?>
                <li><a href="/category/<?php echo $acronym . '?p=' . $i; ?>" class="<?php echo $selected; ?>"><?php echo $i; ?></a></li>
            <?php
            }
            ?>
            </ol>
        </div>
    </div>
</main>