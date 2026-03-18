<?php
// criar_imagem_padrao.php - Execute este arquivo uma vez para criar a imagem padrão
$image = imagecreate(200, 200);
$bg = imagecolorallocate($image, 200, 200, 200);
$text_color = imagecolorallocate($image, 100, 100, 100);
imagestring($image, 5, 50, 90, "Médico", $text_color);
imagejpeg($image, 'uploads/medicos/default-doctor.jpg');
imagedestroy($image);
echo "Imagem padrão criada com sucesso!";
?>