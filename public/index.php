<?php

// Incluir o autoload
require_once __DIR__ .'/../vendor/autoload.php';

// Incluir o arquivo com as variáveis
require_once __DIR__ .'/../config/config.php';

session_start();

// Importar as classe Locadora e Auth
use Services\{Locadora, Auth};

// Importar as classes Carro e Moto
use Models\{Carro, Moto};

// Verifica se o usuário está logado 
if(!Auth::verificarLogin()){
    header('Location: login.php');
    exit;
}

// Condição para logout
if(isset($_GET['logout'])){
    (new Auth())->Logout();
    header('Location: login.php');
    exit;
}

// Criar uma instância da classe locadora
$locadora = new Locadora();

$mensagem = '';

$usuario = Auth::getUsuario();

// Verifica os dados do formulãrio via POST
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // Verificar se requer permissão do administrador
    if(isset($_POST['adicionar']) || isset($_POST['deletar']) || isset($_POST['devolver'])){
        
        if(!Auth::isAdmin()){
            $mensagem = "Você não tem permissão para realizar essa ação.";
            goto renderizar;
        }
    }

    if(isset($_POST['adicionar'])){
        $modelo = $_POST['modelo'];
        $placa = $_POST['placa'];
        $tipo = $_POST['tipo'];

        $veiculo = ($tipo == 'Carro') ? new Carro($modelo, $placa) : new Moto($modelo, $placa);
        
        $locadora->adicionarVeiculo($veiculo);
        
        $mensagem = "Veículo adicionado com sucesso!";
    }
    elseif(isset($_POST['alugar'])){

        $dias = isset($_POST['dias']) ? (int)$_POST['dias'] : 1;
        $mensagem = $locadora->alugarVeiculo($_POST['modelo'], $dias);
    }
    elseif(isset($_POST['devolver'])){
        $mensagem = $locadora->devolverVeiculo($_POST['modelo']);
    }
    elseif(isset($_POST['deletar'])){
        $mensagem = $locadora->deletarVeiculo($_POST['modelo'], $_POST['placa']);
    }
    elseif(isset($_POST['calcular'])){
        $dias = (int)$_POST['dias_calculo'];
        $tipo = $_POST['tipo_calculo'];
        $valor = $locadora->calcularPrevisaoAluguel($dias, $tipo);

        $mensagem = "Previsão de valor para {$dias} dias: R$ " . number_format($valor, 2, ',','.');
    }

}
renderizar:
require_once __DIR__ .'/../views/template.php';
?>