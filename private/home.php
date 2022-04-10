<section id="front-banner">
    <div>
        <div>
            <img src="/images/web-interface.svg" alt="web директория" width="300" height="300">
        </div>
        <div>
            <h1>Каталог на българските уеб сайтове</h1>
            <ul>
                <li>✔ Въведете кратка информация с която да опишете вашия сайт или бизнес</li>
                <li>✔ Добавете url адрес и данни за контакт</li>
                <li>✔ Безплатно добавяне на един или повече сайтове</li>
                <li>✔ Търсене по категории или ключови думи</li>
            </ul>
        </div>
    </div>
</section>

<?php
$websites = $pdo->query("SELECT * FROM Websites WHERE Active = 1 ORDER BY Id DESC LIMIT 10");
?>
<main>
    <div>
        <div id="main-left">
            <strong>Избрани категории</strong>
            <ul id="category-highlight-list">
                <li><a href="/category/култура-изкуство-образование"><img src="images/folder.svg" alt="категория Култура, Изкуство и Образование"> Култура, Изкуство и Образование</a></li>
                <li><a href="/category/потребителски-стоки-сслуги"><img src="images/folder.svg" alt="категория Потребителски Cтоки и Услуги"> Потребителски Cтоки и Услуги</a></li>
                <li><a href="/category/реклама-рекламни-материали"><img src="images/folder.svg" alt="категория Реклама и рекламни материали"> Реклама и рекламни материали</a></li>
                <li><a href="/category/интернет-търговия"><img src="images/folder.svg" alt="категория Интернет търговия"> Интернет търговия</a></li>
                <li><a href="/category/траурни-погребални-услуги"><img src="images/folder.svg" alt="категория Траурни и погребални услуги"> Траурни и погребални услуги</a></li>
                <li><a href="/category/хотел-екохотел-хижа"><img src="images/folder.svg" alt="категория Хотел, екохотел, хижа"> Хотел, екохотел, хижа</a></li>
            </ul>
            <strong class="section-heading">Последно добавени</strong>
            <ul class="website-card">
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
            <div>
                <div class="ext-link">
                    <a href="https://mug3.eu" title="Направи си чаша със снимка и текст"><img src="/images/mug-white.jpg" width="240" height="262" alt="Сайт за чаши със снимки"></a>
                    <a href="https://mug3.eu" title="Чаша със снимки и надпис">Направи си сам чаша със снимки и надпис</a>
                </div>
            </div>
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
                <li><a href="/category/<?php echo $c['Acronym']; ?>"><img src="images/folder.svg" alt="категория <?php echo htmlspecialchars($c['Name']); ?>"> <?php echo $c['Name']; ?></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
</section>