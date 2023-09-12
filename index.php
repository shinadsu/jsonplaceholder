
<?php

class JsonPlaceholderAPI {
    private $base_url = "https://jsonplaceholder.typicode.com";

    public function getUsers() {
        $url = $this->base_url . "/users";
        return $this->sendRequest($url);
    }

    public function getPostsByUser($user_id) {
        $url = $this->base_url . "/users/{$user_id}/posts";
        return $this->sendRequest($url);
    }

    public function getTodosByUser($user_id) {
        $url = $this->base_url . "/users/{$user_id}/todos";
        return $this->sendRequest($url);
    }

    public function getPost($post_id) {
        $url = $this->base_url . "/posts/{$post_id}";
        return $this->sendRequest($url);
    }

    public function createPost($user_id, $title, $body) {
        $url = $this->base_url . "/posts";
        $data = [
            'userId' => $user_id,
            'title' => $title,
            'body' => $body
        ];
        return $this->sendRequest($url, 'POST', $data);
    }

    public function updatePost($post_id, $title, $body) {
        $url = $this->base_url . "/posts/{$post_id}";
        $data = [
            'title' => $title,
            'body' => $body
        ];
        return $this->sendRequest($url, 'PUT', $data);
    }

    public function deletePost($post_id) {
        $url = $this->base_url . "/posts/{$post_id}";
        return $this->sendRequest($url, 'DELETE');
    }

	public function sendRequest($url, $method = 'GET', $data = null) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		if ($method === 'POST' || $method === 'PUT') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Content-Length: ' . strlen(json_encode($data))
			]);
		} elseif ($method === 'DELETE') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		
		// Отключение проверки сертификатов SSL (для тестирования)
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
		$response = curl_exec($ch);
		curl_close($ch);
	
		return json_decode($response, true);
	}
	
}

// Пример использования класса
$api = new JsonPlaceholderAPI();

// Получение пользователей
$users = $api->getUsers();
print_r($users);

// Получение постов для пользователя с ID 1
$posts = $api->getPostsByUser(1);
print_r($posts);

// Создание нового поста
$newPost = $api->createPost(1, "Новый пост", "Текст нового поста");
print_r($newPost);

// Редактирование поста с ID 1
$updatedPost = $api->updatePost(1, "Обновленный пост", "Текст обновленного поста");
print_r($updatedPost);

// Удаление поста с ID 1
$deletedPost = $api->deletePost(1);
print_r($deletedPost);

// Получение заданий для пользователя с ID 1
$userTodos = $api->getTodosByUser(1);
print_r($userTodos);

?>
