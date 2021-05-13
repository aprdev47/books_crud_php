<?php
namespace App\Lib;

class DB
{
    protected static $_instance = null;
    public $connection = null;
    private $db_host,$db_username,$db_password,$db_name;

    public function __construct()
    {
        $this->db_host = Config::get('DB_HOST');
        $this->db_username = Config::get('DB_USERNAME');
        $this->db_password = Config::get('DB_PASSWORD');
        $this->db_name = Config::get('DB_NAME');
    }
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function getConnection()
    {
        if (is_null($this->connection)) {
            try{
                $dsn = "mysql:host={$this->db_host};dbname={$this->db_name}";
                $this->connection = new \PDO($dsn, $this->db_username, $this->db_password);
            } catch(\PDOException $exception){
                echo "Database could not be connected: " . $exception->getMessage();
            }
        }
        return $this->connection;
    }
    public function queryData($query) {
        $args = func_get_args();
        array_shift($args);
        $statement = $this->getConnection()->prepare($query);
        $statement->execute($args);
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }
    public function query($query) {
        $args = func_get_args();
        array_shift($args);
        $statement = $this->getConnection()->prepare($query);
        if($statement->execute($args)){
            return true;
        }
        return false;
    }
}
?>

