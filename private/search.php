<?php

$search_for = trim(filter_input(INPUT_GET, 'n'));
$category = filter_input(INPUT_GET, 'c', FILTER_VALIDATE_INT);
$stop_search = false;
if (mb_strlen($search_for) === 0 || mb_strlen($search_for) > 100)
{
    $stop_search = true;
}

if (!$stop_search && $category > 0)
{
    $categoryExists = $pdo->prepare("SELECT COUNT(*) AS n FROM categories WHERE Id = :id");
    $categoryExists->execute([':id' => $category]);
    if ($categoryExists->fetch()['n'] != 1)
    {
        header('Location: /');
        exit;
    }

    $websites = $pdo->prepare("SELECT * FROM Websites
        WHERE
            Active = 1
            AND CategoryId = ?
            AND (Title LIKE ?
                OR Description LIKE ?
                OR Url LIKE ?)
    ORDER BY Id DESC");
    $websites->execute([$category, '%' . $search_for . '%', '%' . $search_for . '%', '%' . $search_for . '%']);
}
else if (!$stop_search)
{
    $websites = $pdo->prepare("SELECT * FROM Websites
    WHERE
        Active = 1
        AND (Title LIKE ?
            OR Description LIKE ?
            OR Url LIKE ?)
    ORDER BY Id DESC");
    $websites->execute(['%' . $search_for . '%', '%' . $search_for . '%', '%' . $search_for . '%']);
}

$has_results = false;

?>
<main class="main-form">
    <div>
        <div id="main-left">
            <strong>Резултати за: <?php echo $search_for; ?></strong>
            <?php
            if (!$stop_search)
            {
            ?>
                <ul>
                    <?php
                    foreach ($websites as $website)
                    {
                        $has_results = true;
                    ?>
                        <li>
                            <a href="/site/<?php echo $website['Acronym']; ?>"><?php echo $website['Title']; ?></a>
                            <span><?php echo $website['Description']; ?></span>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            <?php
            }

            if (!$has_results)
            {
            ?>
            Няма резултати
            <?php
            }
            ?>
        </div>
    </div>
</main>