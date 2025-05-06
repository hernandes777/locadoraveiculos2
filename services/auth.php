<?php
// define espaço para organização do código
namespace Services;

class Auth
{
    private array $usuarios = [];

    //Método construtor
    public function __construct()
    {
        $this->carregarUsuarios();
    }

    // Método para carregar usuários do arquivo JSON
    private function carregarUsuarios(): void
    {

        //Verificar se existe o arquivo
        if (file_exists(Arquivo_USUARIOS)) {
            //Lê o conteúdo e decodifica o JSON para o array
            $conteudo = json_decode(file_get_contents(Arquivo_USUARIOS), true);

            // verificar se é um array
            $this->usuarios = is_array($conteudo) ? $conteudo : [];
        } else {
            $this->usuarios = [
                [
                    'username' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'perfil' => 'admin'
                ],
                [
                    'username' => 'usuario',
                    'password' => password_hash('usuario123', PASSWORD_DEFAULT),
                    'perfil' => 'usuario'
                ],
            ];
            $this ->salvarUsuarios();
        }
    }
    // Método para salvar usuários no arquivo JSON
    private function salvarUsuarios(): void
    {
        // Verifica se o diretório existe, caso contrário, cria
        if (!is_dir(DIR_USUARIOS)) {
            mkdir(DIR_USUARIOS, 0777, true);
        }

        // Salva os usuários no arquivo JSON
        file_put_contents(Arquivo_USUARIOS, json_encode($this->usuarios, JSON_PRETTY_PRINT));
    }
    // Método para realizar o login
    public function login(string $username, string $password): bool
    {
        // Verifica se o usuário existe e a senha está correta
        foreach ($this->usuarios as $usuario) {
            if ($usuario['username'] === $username && password_verify($password, $usuario['password'])) {
                $_SESSION['auth'] = [
                    'logado' => true,
                    'username' => $username,
                    'perfil' => $usuario['perfil']
                ];
               return true; //login realizado
            }
        }
        return false; //não realizou login
       
    }
}