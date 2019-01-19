<?php

require_once __DIR__ . '\..\php\vendor\autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Error\FormattedError;


class DB
{
    private static $pdo;
    #Инициализация PDO соединения
    public static function init($config)
    {
        #Создаем PDO соединение
        self::$pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}", $config['username'], $config['password']);
        #Задаем режим выборки по умолчанию
        self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }
    #Выполнение запроса select и возвращение одной строки
    public static function selectOne($query)
    {
        $records = self::select($query);
        return array_shift($records);
    }
    #Выполнение запроса select и возвращение строк
    public static function select($query)
    {
        $statement = self::$pdo->query($query);
        return $statement->fetchAll();
    }
    #Выполнение запроса и возвращение количества затронутых строк
    public static function affectingStatement($query)
    {
        $statement = self::$pdo->query($query);
        return $statement->rowCount();
    }
}
class Types
{
	#@var QueryType
    private static $query;
    #@var productType
    private static $product;
    #@return QueryType
    public static function query()
    {
        return self::$query ?: (self::$query = new QueryType());
    }
    #@return productType
    public static function product()
    {
        return self::$product ?: (self::$product = new productType());
    }
    #@return \GraphQL\Type\Definition\IntType
    public static function int()
    {
        return Type::int();
    }
    #@return \GraphQL\Type\Definition\StringType
    public static function string()
    {
        return Type::string();
    }
    #@param \GraphQL\Type\Definition\Type $type
    #@return \GraphQL\Type\Definition\ListOfType
    public static function listOf($type)
    {
        return Type::listOf($type);
    }
}

//описание продукта
class productType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Пользователь',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::string(),
                        'description' => 'Идентификатор продукта'
                    ],
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'Название продукта'
                    ],
                    'cost' => [
                        'type' => Types::string(),
                        'description' => 'Стоимость продукта'
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}

//описание запроса
class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => function() {
                return [
                    'product' => [
                        'type' => Types::product(),
                        'description' => 'Возвращает продукт по id',
                        'args' => [
                            'id' => Types::int()
                        ],
                        'resolve' => function ($root, $args) {
                            return DB::selectOne("SELECT * from product WHERE id = {$args['id']}");
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}

try {
    // Настройки подключения к БД
    $config = [
        'host' => 'localhost',
        'database' => 'test',
        'username' => 'root',
        'password' => ''
    ];
    // Инициализация соединения с БД
    DB::init($config);
    // Получение запроса
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    // Создание схемы
    $schema = new Schema([
        'query' => Types::query()
    ]);
    // Выполнение запроса
    $result = GraphQL::executeQuery($schema, $query);

} catch (\Exception $e) {
    $result = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}
	// Вывод результата
	header('Content-Type: application/json; charset=UTF-8');
	echo json_encode($result);