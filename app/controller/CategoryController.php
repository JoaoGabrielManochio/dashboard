<?php

namespace app\controller;

use app\core\Controller;
use app\model\CategoryModel;
use app\classes\Input;
use app\model\LogModel;

class CategoryController extends Controller
{
    private $categoryModel;
    private $logModel;

    /**
     * Método construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->logModel = new LogModel();
    }

    /**
     * Carrega a página principal
     *
     * @return void
     */
    public function index()
    {
        $data['categories'] = $this->categoryModel->getAll();

        $this->load('category/categories', $data);
    }

    /**
     * Carrega a página com o formulário para cadastrar uma nova categoria
     *
     * @return void
     */
    public function add()
    {
        $this->load('category/addCategory');
    }

    public function insert()
    {
        $category = $this->getInput();

        if (!$this->validate($category, false)) {
            return  $this->showMessage(
                'Formulário inválido',
                'Os dados fornecidos são inválidos',
                BASE  . 'new-category/',
                422
            );
        }

        $result = $this->categoryModel->insert($category);

        $params = [
            'request' => json_encode($category),
            'action' => 'new-category'
        ];

        $this->logModel->insert((object)$params);

        redirect(BASE . 'category/');
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
            'name'      => Input::post('name'),
            'code'      => Input::post('code')
        ];
    }

    /**
     * Valida se os campos recebidos estão válidos
     *
     * @param  Object $category
     * @param  bool $validateId
     * @return bool
     */
    private function validate(Object $category, bool $validateId = true)
    {
        if ($validateId && $category->id <= 0)
            return false;

        if (strlen($category->name) < 3)
            return false;

        if (strlen($category->code) < 1)
            return false;

        return true;
    }
}
