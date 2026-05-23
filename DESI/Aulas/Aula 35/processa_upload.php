<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        
        $nomeOriginal   = $_FILES['arquivo']['name'];
        $tipoMime       = $_FILES['arquivo']['type'];
        $caminhoTemp    = $_FILES['arquivo']['tmp_name'];
        $codigoErro     = $_FILES['arquivo']['error'];
        $tamanhoBytes   = $_FILES['arquivo']['size'];

        echo "<h2>Dados Recebidos da Superglobal \$_FILES:</h2>";
        echo "<ul>";
        echo "<li><strong>name (Nome original):</strong> " . htmlspecialchars($nomeOriginal) . "</li>";
        echo "<li><strong>type (Tipo MIME):</strong> " . htmlspecialchars($tipoMime) . "</li>";
        echo "<li><strong>tmp_name (Caminho temporário):</strong> " . htmlspecialchars($caminhoTemp) . "</li>";
        echo "<li><strong>error (Código de erro):</strong> " . $codigoErro . " (Sucesso!)</li>";
        echo "<li><strong>size (Tamanho):</strong> " . $tamanhoBytes . " bytes (~" . round($tamanhoBytes / 1024, 2) . " KB)</li>";
        echo "</ul>";
        echo "<hr>";

        $pastaDestino = 'uploads/';
        
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0755, true);
        }

        $caminhoFinal = $pastaDestino . basename($nomeOriginal);

        if (move_uploaded_file($arquivo['tmp_name'], $caminho_final)) {
        echo "<h3>Upload realizado com sucesso!</h3>";
        echo "<p><strong>Nome Original:</strong> " . htmlspecialchars($nome_original) . "</p>";
        
        $caminho_seguro_html = htmlspecialchars($caminho_final, ENT_QUOTES, 'UTF-8');
        echo "<img src='$caminho_seguro_html' alt='Imagem de Perfil' width='200'>";
        echo "<br><br>"; 
        echo "<a href='index.html' style='display: inline-block; padding: 8px 15px; background-color: #007BFF; color: white; text-decoration: none; border-radius: 4px;'>← Voltar para o Formulário</a>";
    } else {
            echo "<p style='color: red;'><strong>Erro:</strong> Falha ao mover o arquivo temporário.</p>";
        }

    } else {
        $erro = isset($_FILES['arquivo']) ? $_FILES['arquivo']['error'] : 'Nenhum arquivo enviado';
        echo "<p style='color: red;'><strong>Erro no upload:</strong> Código do erro: $erro</p>";
    }
} else {
    echo "<p style='color: orange;'>Por favor, envie o formulário HTML primeiro.</p>";
}
?>
