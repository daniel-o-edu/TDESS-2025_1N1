// Passo 1: Selecionar o formulário pelo ID
const form = document.querySelector('#formCadastro');

// Passo 2: "Escutar" o evento de envio (submit)
form.addEventListener('submit', (e) => {
    // Evita que a página recarregue ao clicar no botão
    e.preventDefault(); 
    
    // Passo 3: Capturar os valores digitados pelo usuário
    const email = document.querySelector('#email').value;
    const senha = document.querySelector('#senha').value;
    
    // Definir a Expressão Regular (RegEx) para o formato de e-mail
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Variável de controle para saber se podemos enviar os dados
    let formValido = true;

    // Passo 4: Validar o e-mail usando o método .test()
    if (!regexEmail.test(email)) {
        // Se o e-mail for inválido, mostramos a mensagem de erro em vermelho
        document.querySelector('#erroEmail').style.display = 'block';
        formValido = false; // Reprova o formulário
    } else {
        // Se for válido, garantimos que a mensagem de erro fique escondida
        document.querySelector('#erroEmail').style.display = 'none';
    }

    // Passo 5: Veredito Final (E-mail válido E senha com tamanho correto)
    if (formValido && senha.length >= 6) {
        alert("Cadastro realizado com sucesso! Dados prontos para o servidor.");
        form.reset(); // Limpa os campos para o próximo cadastro
    } else if (senha.length < 6) {
        // Alerta caso a senha não tenha o tamanho mínimo
        alert("A senha precisa ter pelo menos 6 caracteres.");
    }
});
