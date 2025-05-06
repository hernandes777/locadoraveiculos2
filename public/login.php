
<?php

// Incluir o autoload do composer para carregar automaticamente as classes utilizadas
require_once __DIR__.'/../vendor/autoload.php';

// Incluir o arquivo com as variáveis
require_once __DIR__.'/../config/config.php';

session_start();

// Inserir a classe de autenticação
use Services\Auth;

// Inicializa a variável para mensagem de erro
$mensagem = '';

// Instanciar a classe de autenticação
$auth = new Auth();

// Verificar se já foi autenticado
if(Auth::verificarLogin()){

    //Redirecionar para a página principal
    header('Location:index.php');
    exit;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $username = $_POST['username'] ?? '';

    $password = $_POST['password'] ?? '';

    if($auth->login($username, $password)){
        // Se o login for correto direciona para página inicial
        header('Location: index.php');
        exit;

    } else {
        $mensagem = 'Falha ao executar o login! Verifique se o usuário e a senha estão corretos.';
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Locadora de veículos</title>
    <!-- Link do bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Link dos ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS Interno -->
    <style>
        .login-container{
            max-width:400px;
            margin: 100px auto;
        }
        .password-toggle{
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
    <div class="login-container">
        <div class="card">
            <!-- Título do card -->
            <div class="card-header">
                <h4 class="mb-0">Login</h4>
            </div>

            <!-- Corpo do card -->
            <div class="card-body">

            <?php if ($mensagem): ?>
                <div class="alert alert-danger"><?=htmlspecialchars($mensagem) ?></div>
                <?php endif; ?>

                <form method="post" class="needs-validation" novalidate>
                

                    <div class="mb-3">
                        <label for="user" class="form-label">
                            Usuário:
                        </label>
                        <input type="text" name="username" class="form-control" required autocomplete="off" placeholder="Digite o usuário">
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">
                            Senha:
                        </label>
                        <input type="password" name="password" class="form-control" id="password" required>
                        <span class="password-toggle mt-3" onclick="togglePassword()">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(){
            let passwordInput = document.getElementById('password');
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
        }
    </script>
</body>
</html>
