<?php

namespace app\controller;

use app\core\Controller;
use app\model\ProductModel;
use app\classes\Input;
use app\model\CategoryModel;
use app\model\LogModel;
use app\model\ProductCategoryModel;

class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;
    private $productCategoryModel;
    private $logModel;

    /**
     * Método construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->productCategoryModel = new ProductCategoryModel();
        $this->logModel = new LogModel();
    }

    /**
     * Carrega a página principal
     *
     * @return void
     */
    public function index()
    {
        $products = $this->productModel->getAll();

        foreach ($products as $product) {
            $product_categories = $this->productCategoryModel->getByProductId($product->id);
            $products_categories = [];

            if (!is_null($product_categories)) {
                foreach ($product_categories as $product_category) {

                    if ($product_category->category_id) {
                        $get_category = $this->categoryModel->getById($product_category->category_id);

                        $products_categories[] = $get_category->name;
                    }
                }

                $product->categories = $products_categories;
            }
        }

        $data['products'] = $products;
        $data['product_categories'] = $products_categories;

        $this->load('product/products', $data);
    }

    /**
     * Carrega a página com o formulário para cadastrar um novo produto
     *
     * @return void
     */
    public function add()
    {
        $data['categories'] = $this->categoryModel->getAll();

        $this->load('product/addProduct', $data);
    }

    public function insert()
    {
        $product = $this->getInput();

        if (!$this->validate($product, false)) {
            return  $this->showMessage(
                'Formulário inválido',
                'Os dados fornecidos são inválidos',
                BASE  . 'new-product/',
                422
            );
        }

        $result = $this->productModel->insert($product);

        if ($result) {
            $product->product_id = $result;
            $this->productCategoryModel->insert($product);
        }

        $params = [
            'request' => json_encode($product),
            'action' => 'new-product'
        ];

        $this->logModel->insert((object)$params);

        redirect(BASE . 'product/');
    }

    /**
     * Retorna os dados do formulário em uma classe padrão stdObject
     *
     * @return object
     */
    private function getInput()
    {
        return (object)[
            'id'        => Input::get('id', FILTER_SANITIZE_NUMBER_INT),
            'sku'      => Input::post('sku'),
            'name'      => Input::post('name'),
            'price'      => Input::post('price'),
            'quantity'      => Input::post('quantity'),
            'category_id'    => Input::post('category_id'),
            'description' => Input::post('description')
        ];
    }

    /**
     * Valida se os campos recebidos estão válidos
     *
     * @param  Object $product
     * @param  bool $validateId
     * @return bool
     */
    private function validate(Object $product, bool $validateId = true)
    {
        if ($validateId && $product->id <= 0)
            return false;

        if (strlen($product->name) < 3)
            return false;

        if (strlen($product->sku) < 3)
            return false;

        if ($product->price <= 0)
            return false;

        if (strlen($product->description) < 3)
            return false;

        // if (strlen($product->imagem) < 5)
        //     return false;

        return true;
    }
}
