<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ToDoAppCLI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:toDO';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ToDo App CLI';

    /**
     * Execute the console command.
     */

    private $token = null;
    private $apiUrl = 'http://localhost:8000/api';

    public function handle()
    {
        //
        $this->info('ToDo App CLI');
        while (true) {
            $command = $this->ask('¿Qué deseas hacer?
            [1] Registrarse
            [2] Login
            [3] Salir
            ');

            if ($command == 3) {
                $this->info('Saliendo del CLI...');
                break;
            }

            switch ($command) {
                case 1:
                    $this->registerUser();
                    break;
                case 2:
                    $this->loginUser();
                    break;
            }
        }
    }

    private function registerUser()
    {
        $name = $this->ask('Ingrese su nombre');
        $email = $this->ask('Ingrese su correo electrónico');
        $password = $this->secret('Ingrese su contraseña');

        $response = Http::post("{$this->apiUrl}/register", [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $this->info('Usuario registrado con éxito. Ahora puedes iniciar sesión.');
        } else {
            $this->error('Error al registrarse: ' . $response->json('message'));
        }
    }

    private function loginUser()
    {
        $email = $this->ask('Ingrese su correo electrónico');
        $password = $this->secret('Ingrese su contraseña');

        $response = Http::post("{$this->apiUrl}/login", [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $this->token = $response->json('token'); // Guardamos el token
            $this->info("Inicio de sesión exitoso. Bienvenido, {$response->json('user')['name']}.");
            $this->taskMenu();
        } else {
            $this->error('Credenciales incorrectas.');
        }
    }

    private function taskMenu()
    {
        if (!$this->token) {
            $this->error('No tienes sesión activa.');
            return;
        }

        while (true) {
            $command = $this->ask('¿Qué deseas hacer?
            [1] Crear tarea
            [2] Listar tareas
            [3] Completar tarea
            [4] Buscar tarea por título
            [5] Eliminar tarea
            [6] Cerrar sesión
            ');

            if ($command == 1) {
                $this->createTask();
            } elseif ($command == 2) {
                $this->listTasks();
            } elseif ($command == 3) {
                $this->completeTask();
            } elseif ($command == 4) {
                $this->searchTask();
            } elseif ($command == 5) {
                $this->deleteTask();
            } elseif ($command == 6) {
                $this->info('Cerrando sesión...');
                $this->token = null;
                break;
            } else {
                $this->error('Opción no válida');
            }
        }
    }

    private function createTask()
    {
        $titulo = $this->ask('Ingrese el título de la tarea');
        $descripcion = $this->ask('Ingrese la descripción de la tarea');

        $response = Http::withToken($this->token)->post("{$this->apiUrl}/tareas", [
            'Titulo' => $titulo,
            'Descripcion' => $descripcion,
        ]);

        if ($response->successful()) {
            $this->info("Tarea '{$titulo}' creada con éxito.");
        } else {
            $this->error('Error al crear la tarea: ' . $response->json('message'));
        }
    }

    private function listTasks()
    {
        $response = Http::withToken($this->token)->get("{$this->apiUrl}/tareas");

        if ($response->successful()) {
            $tasks = $response->json();
            if (empty($tasks)) {
                $this->info('No tienes tareas registradas.');
                return;
            }

            foreach ($tasks as $task) {
                $this->info("[ID: {$task['id']}] - {$task['Titulo']}: {$task['Descripcion']} | Completada: " . ($task['Completada'] ? 'Sí' : 'No'));
            }
        } else {
            $this->error('Error al listar tareas: ' . $response->json('message'));
        }
    }


    private function completeTask()
    {
        $response = Http::withToken($this->token)->get("{$this->apiUrl}/tareas");

        if (!$response->successful()) {
            $this->error('Error al listar tareas: ' . ($response->json('message') ?? 'Error desconocido.'));
            return;
        }

        $tasks = $response->json();
        if (empty($tasks)) {
            $this->info('No tienes tareas registradas.');
            return;
        }

        foreach ($tasks as $task) {
            $this->info("[ID: {$task['id']}] - {$task['Titulo']}: {$task['Descripcion']} | Completada: " . ($task['Completada'] ? 'Sí' : 'No'));
        }

        do {
            $id = $this->ask('Ingrese el ID de la tarea a completar');
            if (!is_numeric($id)) {
                $this->error('El ID debe ser un número.');
            }
        } while (!is_numeric($id));

        $response = Http::withToken($this->token)->patch("{$this->apiUrl}/tareas/{$id}/completar", [
            'Completada' => true
        ]);

        if ($response->successful()) {
            $this->info('Tarea completada con éxito.');
        } else {
            $this->error('Error al completar la tarea: ' . ($response->json('message') ?? 'Error desconocido.'));
        }
    }



    private function searchTask()
    {
        $search = $this->ask('Ingrese el título de la tarea a buscar');

        $response = Http::withToken($this->token)->get("{$this->apiUrl}/tareas/buscar", [
            'search' => $search
        ]);

        if ($response->successful()) {
            $tasks = $response->json();
            if (empty($tasks)) {
                $this->info('No se encontraron tareas con ese título.');
                return;
            }

            foreach ($tasks as $task) {
                $this->info("[ID: {$task['id']}] - {$task['Titulo']}: {$task['Descripcion']}");
            }
        } else {
            $this->error('Error al buscar tarea: ' . $response->json('message'));
        }
    }

    private function deleteTask()
    {
        $id = $this->ask('Ingrese el ID de la tarea a eliminar');

        $response = Http::withToken($this->token)->delete("{$this->apiUrl}/tareas/{$id}");

        if ($response->successful()) {
            $this->info('Tarea eliminada con éxito.');
        } else {
            $this->error('Error al eliminar la tarea: ' . $response->json('message'));
        }
    }
}
