<?php

namespace app\core;

class Controller
{
    protected function load(string $view, $params = [])
    {
        $twig = new \Twig\Environment(
            new \Twig\Loader\FilesystemLoader('../app/view/')
        );

        $twig->addGlobal('BASE', BASE);
        echo $twig->render($view . '.html', $params);
    }

    public function showMessage(string $title, string $message, string $link = null, int $httpCode = 200)
    {
        http_response_code($httpCode);

        $params = [
            'title'    => $title,
            'message' => $message,
            'link'      => $link
        ];

        $this->load('message/main', $params);
    }
}
