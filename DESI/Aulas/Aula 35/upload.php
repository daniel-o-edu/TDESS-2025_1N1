<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
    $arquivo = $_FILES['arquivo'];
    $pasta_destino = "uploads/"; 

    if (!is_dir($pasta_destino)) {
        mkdir($pasta_destino, 0755, true);
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        echo "<h3>Erro no upload. Código: " . $arquivo['error'] . "</h3>";
        echo "<br><br>";
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
        exit;
    }

    $tamanho_maximo = 2 * 1024 * 1024; 
    if ($arquivo['size'] > $tamanho_maximo) {
        echo "<h3>O arquivo é muito grande. Tamanho máximo permitido: 2MB.</h3>";
        echo "<br><br>";
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
        exit;
    }

    $nome_original = $arquivo['name'];
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($extensao, $extensoes_permitidas)) {
        echo "<h3>Extensão de arquivo não permitida. Apenas imagens são aceitas.</h3>";
        echo "<br><br>";
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $tipo_mime_real = finfo_file($finfo, $arquivo['tmp_name']);
    finfo_close($finfo);

    $mimes_permitidos = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($tipo_mime_real, $mimes_permitidos)) {
        echo "<h3>Segurança: O conteúdo do arquivo não corresponde a uma imagem válida!</h3>";
        echo "<br><br>";
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
        exit;
    }

    $novo_nome = bin2hex(random_bytes(16)) . "." . $extensao;
    $caminho_final = $pasta_destino . $novo_nome;

    if (move_uploaded_file($arquivo['tmp_name'], $caminho_final)) {
        if ($extensao === 'jpg' || $extensao === 'jpeg') {
            require_once 'manipula.php';
            redimensionarImagem($caminho_final, 500);
        }

        echo "<h3>Upload realizado com sucesso!</h3>";
        echo "<p><strong>Nome Original:</strong> " . htmlspecialchars($nome_original) . "</p>";
        
        $caminho_seguro_html = htmlspecialchars($caminho_final, ENT_QUOTES, 'UTF-8');
        echo "<img src='$caminho_seguro_html' alt='Imagem de Perfil' width='200'>";
        echo "<br><br>"; 
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
    } else {
        echo "<h3>Erro ao mover o arquivo para a pasta de destino.</h3>";
        echo "<br><br>";
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
    }
} else {
    echo "<h3>Por favor, envie o formulário primeiro.</h3>";
    echo "<br><br>";
    echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
}
?>
