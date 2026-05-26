<?php
function redimensionarImagem($caminho_imagem, $largura_desejada) {
    $imagem_original = imagecreatefromjpeg($caminho_imagem);
    
    $largura_original = imagesx($imagem_original);
    $altura_original = imagesy($imagem_original);
    
    $proporcao = $largura_original / $altura_original;
    $altura_desejada = $largura_desejada / $proporcao;
    
    $nova_imagem = imagecreatetruecolor($largura_desejada, $altura_desejada);
    
    imagecopyresampled(
        $nova_imagem, $imagem_original, 
        0, 0, 0, 0, 
        $largura_desejada, $altura_desejada, 
        $largura_original, $altura_original
    );
    
    imagejpeg($nova_imagem, $caminho_imagem, 80);
    
    imagedestroy($imagem_original);
    imagedestroy($nova_imagem);
}
?>
