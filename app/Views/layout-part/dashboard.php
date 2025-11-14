<?php
//Header
$this->renderView('parts/header');
?>

<?php
//Header
$this->renderView('parts/sidebar');
?>

<?php echo  $content; ?>

<?php
$this->renderView('parts/footer')
?>