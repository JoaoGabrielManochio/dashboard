<?php

namespace app\model;

use app\core\Model;

/**
 * Classe responsável por gerenciar a conexão com a tabela logs.
 */
class LogModel
{
    private $pdo;

    /**
     * Método construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->pdo = new Model();
    }

    /**
     * Insere um novo registro na tabela de logs e retorna seu ID em caso de sucesso
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return int Retorna o ID do produto inserido ou -1 em caso de erro
     */
    public function insert(object $params)
    {
        $sql = 'INSERT INTO logs (`request`, `action`, `date_added`) VALUES (:request, :action, NOW())';

        $params = [
            ':request' => $params->request,
            ':action' => $params->action
        ];

        if (!$this->pdo->executeNonQuery($sql, $params))
            return -1; //Código de erro

        return $this->pdo->getLastID();
    }
}
