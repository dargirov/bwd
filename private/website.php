<?php

$category_q = $pdo->prepare("SELECT * FROM Categories WHERE Id = :id");
$category_q->execute([':id' => $website['CategoryId']]);
$category = $category_q->fetch();

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
            <div>Категория: <a href="/category/<?php echo $category['Acronym']; ?>"><?php echo $category['Name']; ?></a></div>
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
                        <li><img src="/images/phone.svg"> <a href="tel:<?php echo htmlspecialchars($website['Phone']); ?>"><?php echo htmlspecialchars($website['Phone']); ?></a></li>
                        <?php
                    }

                    if (mb_strlen($website['Email']) > 0)
                    {
                        ?>
                        <li><img src="/images/email.svg"> <a href="mailto:<?php echo htmlspecialchars($website['Email']); ?>"><?php echo htmlspecialchars($website['Email']); ?></a></li>
                        <?php
                    }

                    if (mb_strlen($website['Url']) > 0)
                    {
                        ?>
                        <li><img src="/images/www.svg"> <a href="<?php echo $website['Url']; ?>" <?php echo $website['Rel'] == 1 ? 'rel="nofollow"' : ''; ?> target="_blank"><?php echo $website['Url']; ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
            <div class="dark">
                <div class="ext-link">
                    <a href="https://mug3.eu" title="Поръчай чаша със снимки за подарък"><img src="/images/mug-dark.jpg" width="240" height="262" alt="Сайт за чаши със снимка"></a>
                    <a href="https://mug3.eu" title="Чаша със снимки и надпис">Поръчай чаша със снимка за подарък или друг повод</a>
                </div>
            </div>
        </div>
    </div>
</main>