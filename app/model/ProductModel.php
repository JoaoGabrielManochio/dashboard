<?php

namespace app\model;

use app\core\Model;

/**
 * Classe responsável por gerenciar a conexão com a tabela product.
 */
class ProductModel
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
     * Insere um novo registro na tabela de product e retorna seu ID em caso de sucesso
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return int Retorna o ID do produto inserido ou -1 em caso de erro
     */
    public function insert(object $params)
    {
        $sql = 'INSERT INTO product (`name`, `image`, `description`, `sku`, `price`, `quantity`, `category_id`) VALUES (:name, :image, :description, :sku, :price, :quantity, :category_id)';

        $params = [
            ':name' => $params->name,
            ':image' => $params->image ?? '',
            ':description' => $params->description,
            ':sku' => $params->sku,
            ':price' => $params->price,
            ':quantity' => $params->quantity,
            ':category_id' => $params->category_id,
        ];

        if (!$this->pdo->executeNonQuery($sql, $params))
            return -1; //Código de erro

        return $this->pdo->getLastID();
    }

    /**
     * Atualiza um registro na base de dados através do ID do produto
     *
     * @param  Object $params Lista com os parâmetros a serem inseridos
     * @return bool True em caso de sucesso e false em caso de erro
     */
    public function update(object $params)
    {
        $sql = 'UPDATE product SET name = :name, image = :image, description = :description, sku = :sku, price = :price, quantity = :quantity, category_id = :category_id WHERE id = :id';

        $params = [
            ':id'        => $params->id,
            ':name'      => $params->name,
            ':image'    => $params->image,
            ':description' => $params->description,
            ':sku' => $params->sku,
            ':price' => $params->price,
            ':quantity' => $params->quantity,
            ':category_id' => $params->category_id,
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
        $sql = 'SELECT id, name, image, description, sku, price, quantity, category_id FROM product ORDER BY name ASC';

        $dt = $this->pdo->executeQuery($sql);

        $listProduct = null;

        foreach ($dt as $dr)
            $listProduct[] = $this->collection($dr);

        return $listProduct;
    }

    /**
     * Retorna um único registro da base de dados através do ID informado
     *
     * @param  int $id ID do objeto a ser retornado
     * @return object Retorna um objeto populado com os dados do produto ou se não encontrar com seus valores nulos
     */
    public function getById(int $id)
    {
        $sql = 'SELECT id, name, image, description, sku, price, quantity, category_id FROM product WHERE id = :id';

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
     * @return object Retorna um objeto devidamente tratado com a estrutura de produtos
     */
    private function collection($param)
    {
        return (object)[
            'id'            => $param['id'] ?? null,
            'name'          => $param['name'] ?? null,
            'image'         => $param['image'] ?? null,
            'description'   => $param['description'] ?? null,
            'sku'           => $param['sku'] ?? null,
            'price'         => $param['price'] ?? null,
            'quantity'      => $param['quantity'] ?? null
        ];
    }
}
