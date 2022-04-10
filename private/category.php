<?php

$websites = $pdo->prepare("SELECT * FROM Websites WHERE Active = 1 AND CategoryId = :category ORDER BY Id DESC");
$websites->execute([':category' => $category_data['Id']]);

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
        </div>
    </div>
</main>