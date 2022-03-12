<?php
header("Content-Type: application/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://bgwebdir.eu/</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>http://bgwebdir.eu/contacts</loc>
        <priority>0.1</priority>
    </url>
    <?php
    $websites = $pdo->query("SELECT Acronym FROM Websites WHERE Active = 1 ORDER BY Id DESC");
    foreach ($websites as $website)
    {
    ?>
    <url>
        <loc>http://bgwebdir.eu/site/<?php echo $website['Acronym']; ?></loc>
    </url>
    <?php
    }
    ?>
</urlset>