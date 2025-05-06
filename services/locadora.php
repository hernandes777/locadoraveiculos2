<?php
namespace Services;
use Models\{Veiculo, Carro, Moto};

/**
 * Classe responsável por gerenciar as operações da locadora
 */
class Locadora {
    private array $veiculos = [];

    public function __construct() {
        $this->carregarVeiculos();
    }

    /**
     * Carrega os veículos do arquivo JSON
     */
    private function carregarVeiculos(): void {
        if (file_exists(ARQUIVO_JSON)) {
            $dados = json_decode(file_get_contents(ARQUIVO_JSON), true);
            foreach ($dados as $dado) {
                if ($dado['tipo'] === 'Carro') {
                    $veiculo = new Carro($dado['modelo'], $dado['placa']);
                } else {
                    $veiculo = new Moto($dado['modelo'], $dado['placa']);
                }
                $veiculo->setDisponivel($dado['disponivel']);
                $this->veiculos[] = $veiculo;
            }
        }
    }

    /**
     * Salva os veículos no arquivo JSON
     */
    private function salvarVeiculos(): void {
        $dados = [];
        foreach ($this->veiculos as $veiculo) {
            $dados[] = [
                'tipo' => ($veiculo instanceof Carro) ? 'Carro' : 'Moto',
                'modelo' => $veiculo->getModelo(),
                'placa' => $veiculo->getPlaca(),
                'disponivel' => $veiculo->isDisponivel()
            ];
        }
        
        // Cria o diretório se não existir
        $dir = dirname(ARQUIVO_JSON);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        file_put_contents(ARQUIVO_JSON, json_encode($dados, JSON_PRETTY_PRINT));
    }

    /**
     * Adiciona um novo veículo à locadora
     */
    public function adicionarVeiculo(Veiculo $veiculo): void {
        $this->veiculos[] = $veiculo;
        $this->salvarVeiculos();
    }

    /**
     * Remove um veículo da locadora
     */
    public function deletarVeiculo(string $modelo, string $placa): string {
        foreach ($this->veiculos as $key => $veiculo) {
            if ($veiculo->getModelo() === $modelo && $veiculo->getPlaca() === $placa) {
                unset($this->veiculos[$key]);
                $this->veiculos = array_values($this->veiculos);
                $this->salvarVeiculos();
                return "Veículo '{$modelo}' removido com sucesso!";
            }
        }
        return "Veículo não encontrado.";
    }

    /**
     * Aluga um veículo por um número específico de dias
     */
    public function alugarVeiculo(string $modelo, int $dias = 1): string {
        foreach ($this->veiculos as $veiculo) {
            if ($veiculo->getModelo() === $modelo && $veiculo->isDisponivel()) {
                $valorAluguel = $veiculo->calcularAluguel($dias);
                $mensagem = $veiculo->alugar();
                $this->salvarVeiculos();
                return $mensagem . " Valor do aluguel: R$ " . number_format($valorAluguel, 2, ',', '.');
            }
        }
        return "Veículo não disponível.";
    }

    /**
     * Devolve um veículo alugado
     */
    public function devolverVeiculo(string $modelo): string {
        foreach ($this->veiculos as $veiculo) {
            if ($veiculo->getModelo() === $modelo && !$veiculo->isDisponivel()) {
                $mensagem = $veiculo->devolver();
                $this->salvarVeiculos();
                return $mensagem;
            }
        }
        return "Veículo não encontrado ou já está disponível.";
    }

    /**
     * Retorna a lista de todos os veículos
     */
    public function listarVeiculos(): array {
        return $this->veiculos;
    }

    /**
     * Calcula uma previsão de valor do aluguel
     */
    public function calcularPrevisaoAluguel(string $tipo, int $dias): float {
        if ($tipo === 'Carro') {
            return (new Carro('', ''))->calcularAluguel($dias);
        }
        return (new Moto('', ''))->calcularAluguel($dias);
    }
}