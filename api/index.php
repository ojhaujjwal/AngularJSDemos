<?php
chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$customers = new ArrayObject(json_decode(file_get_contents(__DIR__ . '/customers.json'), true));

$app = new \Slim\Slim();

$app->get('/customer', function() use ($app, $customers) {
    $app->response->setBody(json_encode($customers));
});

$app->get('/customer/:id', function($id) use ($app, $customers) {
    foreach ($customers as $customer) {
        if ($customer['id'] == $id) {
            $app->response->setBody(json_encode($customer));
            return;
        }
    }

    $app->notFound();
});

$app->post('/customer', function() use ($app, $customers) {
    $postData = $app->request->post();
    if (empty($postData['firstName']) || empty($postData['lastName']) || empty($postData['city'])) {
        $response = ['messages' => ['Please fill all the required field']];
        $app->response->setStatus(400); 
    } else {
        $id = count($customers) + 1;
        $postData['id'] = $id;
        $customers[] = $postData;
        file_put_contents(__DIR__ . '/customers.json', json_encode($customers));
        $response = $postData;
    }
    $app->response->setBody(json_encode($response));  
});

$app->delete('/customer/:id', function($id) use ($app, $customers) {
        foreach ($customers as $key => $customer) {
            if ($customer['id'] == $id) {
                unset($customers[$key]);
                file_put_contents(__DIR__ . '/customers.json', json_encode($customers));
                $app->response->setBody(json_encode(['status' => 'done']));  
                return;
            }
        }
        $app->notFound();
});


$app->notFound(function () use ($app) {
    $app->response->setBody(json_encode(['status'=> 404]));
});

$app->response->headers->set('Content-Type', 'application/json');

$app->run();
