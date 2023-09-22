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
    public static function GetValue($table, $column, $path, $whereColumn, $whereValue, $assocArray = false)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {            
            $statement = $pdo->prepare("SELECT $column FROM $table WHERE $whereColumn = :whereValue");
            $statement->bindParam(':whereValue', $whereValue, PDO::PARAM_STR);
            $statement->execute();
            $result = '';
            if($assocArray == true)
            {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);  
            }
            else
            {
                $result = $statement->fetch();
            }            
            return $result;
        }
        catch(Exception $e)
        {
            self::Catch($e);
        }
    }

    public static function GetRow($table, $path, array $whereColumns, array $whereValues, $assocArray = false)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {
            if (!is_array($whereColumns) || !is_array($whereValues) || count($whereColumns) !== count($whereValues))
            {
                throw new Exception("Invalid input: Columns and values must be arrays, and be of the same length.");
            }

            $whereClauses = [];
            foreach ($whereColumns as $column)
            {
                $whereClauses[] = $column . ' = :' . $column;
            }

            $whereClause = implode(' AND ', $whereClauses);

            $statement = $pdo->prepare("SELECT * FROM $table WHERE $whereClause");
            $bindParams = array_combine($whereColumns, $whereValues);

            $statement->execute($bindParams);
            if($assocArray == true)
            {
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);  
            }
            else
            {
                $result = $statement->fetch();
            }            
            return $result;

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
    public static function Update($table, $path, array $setColumn, array $setValue, $targetColumn, $targetValue)
    {
        $pdo = new PDO('sqlite:' . $path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try
        {
            if (!is_array($setColumn) || !is_array($setValue) || count($setColumn) !== count($setValue))
            {
                throw new Exception("Invalid input: Columns and values must be arrays, and be of the same length.");
            }

            $updateValues = '';

            for($i = 0; $i < count($setColumn); $i++)
            {
                $updateValues .= " {$setColumn[$i]} = {$setValue[$i]}";
                if($i < count($setColumn) - 1)
                {
                    $updateValues .= ', ';
                }
            }

            //Log::WriteLog('test.txt', "UPDATE $table SET $updateValues WHERE $targetColumn = $targetValue");
            $statement = $pdo->prepare("UPDATE $table SET $updateValues WHERE $targetColumn = $targetValue");

            $result = $statement->execute();

            if($result)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e)
        {
            self::Catch($e);
            return false;
        }
    }
}