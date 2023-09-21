<?php
namespace Model\Services;

use Exception;
use PDO;
use Model\Utilities\Log;

class DataAccess
{
    private static function Catch($e)
    {
        Log::WriteLog('error.txt', $e->getMessage());
    }
    
    #region [ GET ]        
    /**
     * @param mixed $table
     * @param mixed $column
     * @param mixed $path
     * 
     * @return [type]
     */
    public static function GetColumn($table, $column, $path)
    {                          
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {            
            $statement = $pdo->prepare("SELECT $column FROM $table");
            $statement->execute();            
            return $statement->fetchAll();
        }
        catch(Exception $e)
        {
            self::Catch($e);
        }
    }

    /**
     * @param mixed $table
     * @param mixed $column
     * @param mixed $path
     * @param mixed $whereColumn
     * @param mixed $whereValue
     * @param bool $isArray
     * 
     * @return [type]
     */
    public static function GetValues($table, $column, $path, $whereColumn, $whereValue)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {            
            $statement = $pdo->prepare("SELECT $column FROM $table WHERE $whereColumn = :whereValue");
            $statement->bindParam(':whereValue', $whereValue, PDO::PARAM_STR);
            $statement->execute();            
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);            
            return $result;
        }
        catch(Exception $e)
        {
            self::Catch($e);
        }
    }

    public static function GetEntries($table, $path, array $columns, array $values)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {
            if (!is_array($columns) || !is_array($values) || count($columns) !== count($values))
            {
                throw new Exception("Invalid input: Columns and values must be arrays, and be of the same length.");
            }

            $whereClauses = [];
            foreach ($columns as $column)
            {
                $whereClauses[] = $column . ' = :' . $column;
            }

            $whereClause = implode(' AND ', $whereClauses);

            $statement = $pdo->prepare("SELECT * FROM $table WHERE $whereClause");
            $bindParams = array_combine($columns, $values);

            $statement->execute($bindParams);

            $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
            
            return $result;
        }
        catch(Exception $e)
        {
            self::Catch($e);           
        }
    }

    public static function GetTable($table, $path)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {
            $statement = $pdo->prepare("SELECT * FROM $table");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(Exception $e)
        {
            self::Catch($e);
        }
    }
    #endregion

    #region [ UPDATE ]
    public static function Update($table, $path)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {

        }
        catch(Exception $e)
        {
            self::Catch($e);
        }
    }
}