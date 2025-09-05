# Sistema REST API per xCrudRevolution

## Proposta: xcrud_api.php - REST API Completa

### Struttura File
```php
// xcrud_api.php - Nuovo endpoint API
<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include('xcrud.php');

class XcrudAPI {
    
    private $xcrud;
    private $auth_token;
    
    public function __construct() {
        // Autenticazione via Bearer Token
        $this->authenticate();
        
        // Parse request
        $this->route();
    }
    
    private function authenticate() {
        $headers = getallheaders();
        $this->auth_token = $headers['Authorization'] ?? '';
        
        // Verifica token (implementare sistema auth)
        if (!$this->verify_token($this->auth_token)) {
            $this->response(401, ['error' => 'Unauthorized']);
        }
    }
    
    private function route() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_GET['path'] ?? '';
        $parts = explode('/', trim($path, '/'));
        
        $table = $parts[0] ?? '';
        $id = $parts[1] ?? null;
        
        if (!$table) {
            $this->response(400, ['error' => 'Table not specified']);
        }
        
        $this->xcrud = Xcrud::get_instance();
        $this->xcrud->table($table);
        
        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getOne($id);
                } else {
                    $this->getAll();
                }
                break;
                
            case 'POST':
                $this->create();
                break;
                
            case 'PUT':
                $this->update($id);
                break;
                
            case 'DELETE':
                $this->delete($id);
                break;
                
            case 'OPTIONS':
                $this->response(200, ['methods' => 'GET, POST, PUT, DELETE']);
                break;
                
            default:
                $this->response(405, ['error' => 'Method not allowed']);
        }
    }
    
    private function getAll() {
        // Query parameters
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 50;
        $search = $_GET['search'] ?? '';
        $orderby = $_GET['orderby'] ?? '';
        $filters = $_GET['filters'] ?? [];
        
        // Apply filters
        if ($search) {
            $this->xcrud->where('', 'LIKE', "%$search%");
        }
        
        if ($filters) {
            foreach ($filters as $field => $value) {
                $this->xcrud->where($field, '=', $value);
            }
        }
        
        if ($orderby) {
            $this->xcrud->order_by($orderby);
        }
        
        $this->xcrud->limit($limit);
        $this->xcrud->start(($page - 1) * $limit);
        
        // Get data
        $data = $this->xcrud->get_list();
        $total = $this->xcrud->total_count();
        
        $this->response(200, [
            'data' => $data,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'pages' => ceil($total / $limit)
            ]
        ]);
    }
    
    private function getOne($id) {
        $data = $this->xcrud->get_instance()->query("
            SELECT * FROM {$this->xcrud->table} 
            WHERE {$this->xcrud->primary} = ?
        ", [$id]);
        
        if ($data) {
            $this->response(200, ['data' => $data[0]]);
        } else {
            $this->response(404, ['error' => 'Not found']);
        }
    }
    
    private function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Validate
        $validation = $this->xcrud->validate($data);
        if (!$validation['success']) {
            $this->response(422, ['errors' => $validation['errors']]);
        }
        
        // Insert
        $id = $this->xcrud->insert($data);
        
        $this->response(201, [
            'message' => 'Created',
            'id' => $id,
            'data' => $data
        ]);
    }
    
    private function update($id) {
        if (!$id) {
            $this->response(400, ['error' => 'ID required']);
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Update
        $success = $this->xcrud->update($id, $data);
        
        if ($success) {
            $this->response(200, [
                'message' => 'Updated',
                'id' => $id,
                'data' => $data
            ]);
        } else {
            $this->response(500, ['error' => 'Update failed']);
        }
    }
    
    private function delete($id) {
        if (!$id) {
            $this->response(400, ['error' => 'ID required']);
        }
        
        $success = $this->xcrud->delete($id);
        
        if ($success) {
            $this->response(200, ['message' => 'Deleted']);
        } else {
            $this->response(500, ['error' => 'Delete failed']);
        }
    }
    
    private function response($code, $data) {
        http_response_code($code);
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}

// Initialize API
new XcrudAPI();
```

### Utilizzo REST API

```javascript
// GET tutti i record
fetch('/xcrud_api.php?path=users', {
    headers: { 'Authorization': 'Bearer YOUR_TOKEN' }
})

// GET record singolo
fetch('/xcrud_api.php?path=users/123')

// POST nuovo record
fetch('/xcrud_api.php?path=users', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer YOUR_TOKEN'
    },
    body: JSON.stringify({
        name: 'John Doe',
        email: 'john@example.com'
    })
})

// PUT aggiorna record
fetch('/xcrud_api.php?path=users/123', {
    method: 'PUT',
    body: JSON.stringify({ name: 'Jane Doe' })
})

// DELETE elimina record
fetch('/xcrud_api.php?path=users/123', {
    method: 'DELETE'
})
```

### Addon API Extension

```php
// addons/api_extension/api_extension.php
class XcrudAddon_ApiExtension {
    
    public function init($xcrud) {
        // Aggiungi metodi API custom
        $xcrud->api_endpoints = [
            'export' => [$this, 'export'],
            'import' => [$this, 'import'],
            'bulk' => [$this, 'bulk_operations']
        ];
    }
    
    public function export($format = 'json') {
        // Export in CSV, Excel, PDF
    }
    
    public function import($file) {
        // Import da CSV, Excel
    }
    
    public function bulk_operations($action, $ids) {
        // Operazioni bulk
    }
}
```

### GraphQL Support (Opzionale)

```php
// xcrud_graphql.php
class XcrudGraphQL {
    // Implementazione GraphQL endpoint
    // Query e Mutations automatiche basate su tabelle
}
```

### WebSocket Support (Opzionale)

```php
// xcrud_websocket.php
class XcrudWebSocket {
    // Real-time updates
    // Push notifications su CRUD operations
}
```

## Vantaggi REST API

1. **Frontend Agnostico**: React, Vue, Angular, Mobile apps
2. **Microservizi Ready**: Architettura API-first
3. **Scalabile**: Cache, rate limiting, load balancing
4. **Documentabile**: Swagger/OpenAPI auto-generato
5. **Testabile**: Unit test su endpoints
6. **Sicuro**: Token auth, CORS, rate limiting