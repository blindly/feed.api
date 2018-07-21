<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "n";
?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
<title><?php echo $title ; ?></title>
<link>
</link>
<admin:generatorAgent rdf:resource="http://www.codeigniter.com/" />
<!-- repeat this block for more items -->
<?php if(is_array($stories)): 
foreach($stories as $row):?>
<item>

<title><?php echo $row['title']; ?></title>
<link><?php echo $row['link']; ?></link>
<guid></guid>
<pubDate><?php echo $row['date']; ?></pubDate>
</item> 
<?php endforeach; 
endif;?>
<!-- end item Block -->
</channel>
</rss>
