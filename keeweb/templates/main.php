<?php
script('keeweb', 'script');
style('keeweb', 'style');
?>

<div id="app">
  <iframe src="<?php p($_['keeweb']); ?><?php isset($_['config']) ? p('?config='.$_['config']) : '' ?>" />
</div>
