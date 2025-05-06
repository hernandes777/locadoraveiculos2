Funcionamento do Sistema de Locação de Veículos com PHP e BooTstrap
Este documento descreve o funcionamento do sistema de locadora de veículos desenvolvido em PHP utilizando Bootstrap para interface, com autenticação de usuários, gerenciamento de veículos (carros e motos) e persistência de dados em arquivos JSON. O foco principal é explicar o funcionamento geral do sistema, com ênfase especial nos perfis de acesso (admin e usuário).

1. Visão Geral do Sistema
O Sistema de locadora de veículos e uma aplicação web que permite:

Autenticação de usuário xom dois perfis: admin (administrador) e usuário
Gerenciamento de veículos: cadastro, alguel, devolução e exclusão;
Cálculo de previsão de aluguel: com base no tipo de veículo (carro ou moto) e número de dias;
Interface responsiva.
Os dados armazenados em dois arquivos JSON:

usuário.json: nome de usuário, senha criptografada e perfil
Veiculos.json: tipo, modelo, placa e status de disponibilidade
2. Estrutura do sistema
O Sistema Utiliza:

PHP : para a lógica
Bootstrap : para estilização
Ícones Bootstrap : para os ícones da interface
Composer : para autoloading de classes
JSON : para persistência de dados
2.1 Componentes principais
Interfaces : Defina uma Interface Locavelpara veículos e utilização dos métodos alugar(), einDisponivel()
Modelos : aulas Veiculos(resumos), Carroe Motopara os veículos, com cálculo de aluguel baseado em diárias constantes ( DIARIA_CARROe DIARIA_MOTO)
Serviços : Aulas AUTH(autenticação e gerenciamento de usuários) e Locadora(gerenciamento de veículos)
Visualizações : Template principal template.phppara renderizar a interface e login.phppara a autenticação
Controladores : Lógica em index.phppara processar requisições e carregar o template.
