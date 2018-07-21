<?xml version="1.0" encoding="UTF-8"?>
<rss>
<channel>
<title><?php echo $feed_name ; ?></title>
<link>
</link>
<!-- repeat this block for more items -->
<?php if(is_array($stories)): 
foreach($stories as $row):?>
<item>

<title><?php echo $row['title']; ?></title>
<link><?php echo $row['link']; ?></link>
<guid><?php echo $row['guid']; ?></guid>
</item> 
<?php endforeach; 
endif;?>
<!-- end item Block -->
</channel>
</rss>
