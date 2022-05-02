<?php

namespace app\model;

use app\core\Model;

/**
 * Classe responsável por gerenciar a conexão com a tabela product_category.
 */
class ProductCategoryModel
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
     * Insere um novo registro na tabela de product_category e retorna seu ID em caso de sucesso
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return int Retorna o ID do product_category inserido ou -1 em caso de erro
     */
    public function insert(object $params)
    {
        $sql = 'INSERT INTO product_category (`product_id`, `category_id`) VALUES (:product_id, :category_id)';

        $params = [
            ':product_id'   => $params->product_id,
            ':category_id'  => $params->category_id
        ];

        if (!$this->pdo->executeNonQuery($sql, $params))
            return -1; //Código de erro

        return $this->pdo->getLastID();
    }

    /**
     * Atualiza um registro na base de dados através do ID do product_category
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return bool True em caso de sucesso e false em caso de erro
     */
    public function update(object $params)
    {
        $sql = 'UPDATE product_category SET product_id = :product_id, category_id = :category_id WHERE id = :id';

        $params = [
            ':id'        => $params->id,
            ':product_id'      => $params->product_id,
            ':category_id'    => $params->category_id,
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
        $sql = 'SELECT id, product_id, category_id FROM product_category';

        $dt = $this->pdo->executeQuery($sql);

        $listProductCategory = null;

        foreach ($dt as $dr)
            $listProductCategory[] = $this->collection($dr);

        return $listProductCategory;
    }

    /**
     * Retorna um único registro da base de dados através do ID informado
     *
     * @param  int $id ID do objeto a ser retornado
     * @return object Retorna um objeto populado com os dados do product_category ou se não encontrar com seus valores nulos
     */
    public function getById(int $id)
    {
        $sql = 'SELECT id, product_id, category_id FROM product_category WHERE id = :id';

        $param = [
            ':id' => $id
        ];

        $dr = $this->pdo->executeQueryOneRow($sql, $param);

        return $this->collection($dr);
    }

     /**
     * Retorna um único registro da base de dados através do ID informado
     *
     * @param  int $id ID do objeto a ser retornado
     * @return object Retorna um objeto populado com os dados do product_category ou se não encontrar com seus valores nulos
     */
   /**
     * Retorna todos os registros da base de dados em ordem ascendente por nome
     *
     * @return arra[object]
     */
    public function getByProductId(int $product_id)
    {
        $sql = 'SELECT id, product_id, category_id FROM product_category WHERE product_id = :product_id';

        $param = [
            ':product_id' => $product_id
        ];

        $dt = $this->pdo->executeQuery($sql, $param);

        $listProductCategory = null;

        foreach ($dt as $dr)
            $listProductCategory[] = $this->collection($dr);

        return $listProductCategory;
    }

    /**
     * Converte uma estrutura de array vinda da base de dados em um objeto devidamente tratado
     *
     * @param  array|object $param Revebe o parâmetro que é retornado na consulta com a base de dados
     * @return object Retorna um objeto devidamente tratado com a estrutura de product_category
     */
    private function collection($param)
    {
        return (object)[
            'id' => $param['id'] ?? null,
            'product_id' => $param['product_id'] ?? null,
            'category_id' => $param['category_id'] ?? null,
        ];
    }
}
