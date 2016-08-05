<?php
style('keeweb', 'style');
?>

<div id="app">
  <iframe src="/index.php/apps/keeweb/keeweb<?php isset($_['config']) ? p('?config='.$_['config']) : '' ?>" />
</div>
