<?php

namespace app\model;

use app\core\Model;

/**
 * Classe responsável por gerenciar a conexão com a tabela category.
 */
class CategoryModel
{
    //Instância da classe model
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
     * Insere um novo registro na tabela de category e retorna seu ID em caso de sucesso
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return int Retorna o ID da categoria inserido ou -1 em caso de erro
     */
    public function insert(object $params)
    {
        $sql = 'INSERT INTO category (`name`, `code`) VALUES (:name, :code)';

        $params = [
            ':name'      => $params->name,
            ':code'    => $params->code
        ];

        if (!$this->pdo->executeNonQuery($sql, $params))
            return -1; //Código de erro

        return $this->pdo->getLastID();
    }

    /**
     * Atualiza um registro na base de dados através do ID do categoria
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return bool True em caso de sucesso e false em caso de erro
     */
    public function update(object $params)
    {
        $sql = 'UPDATE category SET name = :name, code = :code WHERE id = :id';

        $params = [
            ':id'        => $params->id,
            ':name'      => $params->name,
            ':code'    => $params->code,
        ];

        return $this->pdo->executeNonQuery($sql, $params);
    }

    /**
     * Retorna todos os registros da base de dados em ordem ascendente por nome
     *
     * @return arra[object]
     */
    public function getAll()
    {
        $sql = 'SELECT id, name, code FROM category ORDER BY name ASC';

        $dt = $this->pdo->executeQuery($sql);

        $lsitCategory = null;

        foreach ($dt as $dr)
            $lsitCategory[] = $this->collection($dr);

        return $lsitCategory;
    }

    /**
     * Retorna um único registro da base de dados através do ID informado
     *
     * @param  int $id ID do objeto a ser retornado
     * @return object Retorna um objeto populado com os dados da categoria ou se não encontrar com seus valores nulos
     */
    public function getById(int $id)
    {
        $sql = 'SELECT id, name, code FROM category WHERE id = :id';

        $param = [
            ':id' => $id
        ];

        $dr = $this->pdo->executeQueryOneRow($sql, $param);

        return $this->collection($dr);
    }

    /**
     * Converte uma estrutura de array vinda da base de dados em um objeto devidamente tratado
     *
     * @param  array|object $param Revebe o parâmetro que é retornado na consulta com a base de dados
     * @return object Retorna um objeto devidamente tratado com a estrutura de categorias
     */
    private function collection($param)
    {
        return (object)[
            'id'    => $param['id'] ?? null,
            'name'  => $param['name'] ?? null,
            'code'  => $param['code'] ?? null,
        ];
    }
}
