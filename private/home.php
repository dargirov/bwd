<?php
$websites = $pdo->query("SELECT * FROM Websites WHERE Active = 1 ORDER BY Id DESC LIMIT 20");
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
                        <a href="/site/<?php echo $website['Acronym']; ?>"><?php echo $website['Title']; ?> (<?php echo date('d.m.Y', strtotime($website['DateCreated'])); ?>)</a>
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
                <li><a href="/category/<?php echo $c['Acronym']; ?>"><img src="images/folder.svg"> <?php echo $c['Name']; ?></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
</section>