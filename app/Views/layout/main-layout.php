<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<?php
//Header
$this->renderView('parts/header', $getInfor);
?>

<?php
//Header
$this->renderView('parts/sidebar');
?>

<?php echo  $content; ?>

<?php
$this->renderView('parts/footer')
?>

<body>

</body>

</html>