<?php

    $uri = $_SERVER['REQUEST_URI'];

    switch ($uri) {
        case '/':
            $this->get('/', 'DashboardController@home');
            break;
        case '/product/':
            $this->get('/product', 'ProductController@index');
            break;
        case '/new-product/':
            $this->get('/new-product', 'ProductController@add');
            break;
        case '/edit-product/':
            $this->get('/edit-product', 'ProductController@edit');
            break;
        case '/insert-product/':
            $this->get('/insert-product', 'ProductController@insert');
            break;

        case '/category/':
            $this->get('/category', 'CategoryController@index');
            break;
        case '/new-category/':
            $this->get('/new-category', 'CategoryController@add');
            break;
        case '/edit-category/':
            $this->get('/edit-category', 'CategoryController@edit');
            break;
        case '/insert-category/':
            $this->get('/insert-category', 'CategoryController@insert');
            break;
    }
