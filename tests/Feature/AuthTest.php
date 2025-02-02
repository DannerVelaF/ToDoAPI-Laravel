<?php


test('Register success', function () {
    // Refresh database
    $this->artisan('migrate:fresh');

    $response = $this->post('/api/register', [
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => 'adminadm',
    ]);
    dump($response->json()); // Muestra la respuesta en la consola

    $response->assertStatus(200);
});


test('Login success', function () {
    $response = $this->post('/api/login', [
        'email' => 'admin@admin.com',
        'password' => 'adminadm',
    ]);
    dump($response->json()); // Muestra la respuesta en la consola

    $response->assertStatus(200);
});
