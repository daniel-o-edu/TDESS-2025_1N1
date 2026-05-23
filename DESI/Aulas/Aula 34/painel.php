<?php
// painel.php - Gerenciador de Estoque (CRUD de Produtos)

//include 'protect.php';
include 'conexao.php';

$nomeUsuario = $_SESSION['nome'] ?? 'Usuário';
$mensagem    = "";
$tipo_msg    = "";
$produto_editar = null;

// ── Criar tabela se não existir ──────────────────────────
/*$mysqli->query("CREATE TABLE IF NOT EXISTS produtos (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    nome      VARCHAR(150) NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    valor     DECIMAL(10,2) NOT NULL DEFAULT 0.00
)");*/

// ── Ação: EXCLUIR ────────────────────────────────────────
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($mysqli->query("DELETE FROM produtos WHERE id = $id")) {
        $mensagem = "Produto excluído com sucesso.";
        $tipo_msg = "sucesso";
    } else {
        $mensagem = "Erro ao excluir: " . $mysqli->error;
        $tipo_msg = "erro";
    }
}

// ── Ação: CARREGAR DADOS PARA EDITAR ────────────────────
if (isset($_GET['acao']) && $_GET['acao'] === 'editar' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $res = $mysqli->query("SELECT * FROM produtos WHERE id = $id");
    if ($res && $res->num_rows === 1) {
        $produto_editar = $res->fetch_assoc();
    }
}

// ── Ação: SALVAR (novo ou atualização) ──────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome      = $mysqli->real_escape_string(trim($_POST['nome']      ?? ''));
    $quantidade = (int) ($_POST['quantidade'] ?? 0);
    $valor     = number_format((float) str_replace(',', '.', $_POST['valor'] ?? '0'), 2, '.', '');
    $id_post   = (int) ($_POST['id'] ?? 0);

    if (empty($nome)) {
        $mensagem = "O nome do produto é obrigatório.";
        $tipo_msg = "erro";
    } elseif ($quantidade < 0) {
        $mensagem = "A quantidade não pode ser negativa.";
        $tipo_msg = "erro";
    } elseif ($valor < 0) {
        $mensagem = "O valor não pode ser negativo.";
        $tipo_msg = "erro";
    } else {
        if ($id_post > 0) {
            // ATUALIZAR
            $sql = "UPDATE produtos SET nome='$nome', quantidade=$quantidade, valor=$valor WHERE id=$id_post";
            if ($mysqli->query($sql)) {
                $mensagem = "Produto atualizado com sucesso.";
                $tipo_msg = "sucesso";
            } else {
                $mensagem = "Erro ao atualizar: " . $mysqli->error;
                $tipo_msg = "erro";
            }
        } else {
            // INSERIR
            $sql = "INSERT INTO produtos (nome, quantidade, valor) VALUES ('$nome', $quantidade, $valor)";
            if ($mysqli->query($sql)) {
                $mensagem = "Produto cadastrado com sucesso.";
                $tipo_msg = "sucesso";
            } else {
                $mensagem = "Erro ao cadastrar: " . $mysqli->error;
                $tipo_msg = "erro";
            }
        }
        $produto_editar = null;
    }
}

// ── LISTAR todos os produtos ─────────────────────────────
$lista = $mysqli->query("SELECT * FROM produtos ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel - Gerenciador de Estoque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="pagina-painel">

    <!-- Barra superior -->
    <header class="topbar">
        <span class="topbar-titulo">&#128230; Gerenciador de Estoque</span>
        <div class="topbar-usuario">
            Usuário: <strong><?= htmlspecialchars($nomeUsuario) ?></strong>
            &nbsp;|&nbsp;
            <a href="logout.php">Sair</a>
        </div>
    </header>

    <main class="container painel-conteudo">

        <!-- Mensagem de feedback -->
        <?php if (!empty($mensagem)): ?>
            <p class="mensagem <?= htmlspecialchars($tipo_msg) ?>">
                <?= htmlspecialchars($mensagem) ?>
            </p>
        <?php endif; ?>

        <!-- ── Formulário: cadastro ou edição ── -->
        <section class="card-form">
            <h2><?= $produto_editar ? 'Editar Produto' : 'Cadastrar Produto' ?></h2>

            <form action="painel.php" method="POST">

                <!-- id oculto só é enviado na edição -->
                <?php if ($produto_editar): ?>
                    <input type="hidden" name="id" value="<?= (int) $produto_editar['id'] ?>">
                <?php endif; ?>

                <div class="form-linha">
                    <div class="campo">
                        <label for="nome">Nome do produto</label>
                        <input type="text" id="nome" name="nome" required
                               value="<?= htmlspecialchars($produto_editar['nome'] ?? '') ?>">
                    </div>
                    <div class="campo campo-pequeno">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" id="quantidade" name="quantidade" min="0" required
                               value="<?= htmlspecialchars($produto_editar['quantidade'] ?? '0') ?>">
                    </div>
                    <div class="campo campo-pequeno">
                        <label for="valor">Valor (R$)</label>
                        <input type="number" id="valor" name="valor" min="0" step="0.01" required
                               value="<?= htmlspecialchars($produto_editar['valor'] ?? '0.00') ?>">
                    </div>
                </div>

                <div class="form-acoes">
                    <button type="submit" class="btn btn-principal">
                        <?= $produto_editar ? 'Salvar alterações' : 'Cadastrar produto' ?>
                    </button>
                    <?php if ($produto_editar): ?>
                        <a href="painel.php" class="btn btn-cancelar">Cancelar</a>
                    <?php endif; ?>
                </div>

            </form>
        </section>

        <!-- ── Tabela de produtos ── -->
        <section class="card-tabela">
            <h2>Produtos cadastrados</h2>

            <?php if ($lista && $lista->num_rows > 0): ?>
                <table class="tabela-produtos">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome</th>
                            <th>Quantidade</th>
                            <th>Valor</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($p = $lista->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $p['id'] ?></td>
                                <td><?= htmlspecialchars($p['nome']) ?></td>
                                <td><?= (int) $p['quantidade'] ?></td>
                                <td>R$ <?= number_format((float) $p['valor'], 2, ',', '.') ?></td>
                                <td class="acoes">
                                    <a href="painel.php?acao=editar&id=<?= (int) $p['id'] ?>"
                                       class="btn-acao btn-editar">Editar</a>
                                    <a href="painel.php?acao=excluir&id=<?= (int) $p['id'] ?>"
                                       class="btn-acao btn-excluir"
                                       onclick="return confirm('Excluir o produto \'<?= htmlspecialchars(addslashes($p['nome'])) ?>\'?')">
                                       Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="sem-registros">Nenhum produto cadastrado ainda.</p>
            <?php endif; ?>
        </section>

    </main>
</body>
</html>
