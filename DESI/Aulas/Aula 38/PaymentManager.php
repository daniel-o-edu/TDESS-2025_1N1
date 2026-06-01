<?php

class PaymentManager
{
    public function processarPagamento($nomeCliente, $cpf, $valor, $metodoPagamento)
    {
        if (empty($nomeCliente) || empty($cpf)) {
            throw new Exception("Erro: Dados do cliente são obrigatórios.");
        }
        
        if (strlen($cpf) !== 11) {
            throw new Exception("Erro: CPF inválido.");
        }

        $taxa = 0;
        if ($metodoPagamento === 'CARTAO_CREDITO') {
            $taxa = $valor * 0.05;
        } elseif ($metodoPagamento === 'BOLETO') {
            $taxa = 2.50; 
        } elseif ($metodoPagamento === 'PIX') {
            $taxa = 0.00;
        } else {
            throw new Exception("Erro: Método de pagamento não suportado.");
        }

        $valorTotal = $valor + $taxa;

        echo "Processando pagamento de R$ " . number_format($valorTotal, 2, ',', '.') . " via {$metodoPagamento}...\n";

        $conteudoRecibo = "--- RECIBO DE PAGAMENTO ---\n";
        $conteudoRecibo .= "Cliente: {$nomeCliente} (CPF: {$cpf})\n";
        $conteudoRecibo .= "Valor Original: R$ " . number_format($valor, 2, ',', '.') . "\n";
        $conteudoRecibo .= "Taxa Aplicada: R$ " . number_format($taxa, 2, ',', '.') . "\n";
        $conteudoRecibo .= "Total Pago: R$ " . number_format($valorTotal, 2, ',', '.') . "\n";
        
        $nomeArquivo = "recibo_" . time() . ".txt";
        $arquivo = fopen($nomeArquivo, "w");
        fwrite($arquivo, $conteudoRecibo);
        fclose($arquivo);
        echo "Recibo salvo em: {$nomeArquivo}\n";

        $this->enviarSmsConfirmacao($cpf, $valorTotal);

        return true;
    }

    private function enviarSmsConfirmacao($cpf, $valorTotal)
    {
        $mensagem = "Pagamento de R$ " . number_format($valorTotal, 2, ',', '.') . " aprovado com sucesso.";
        echo "Enviando SMS para o cliente (CPF: {$cpf}): '{$mensagem}'\n";
    }
}

try {
    $gerenciador = new PaymentManager();
    $gerenciador->processarPagamento("João da Silva", "12345678901", 150.00, "CARTAO_CREDITO");
    echo "\n";
    $gerenciador->processarPagamento("Maria Souza", "10987654321", 300.00, "PIX");
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

?>