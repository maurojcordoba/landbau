<?php
class Database
{
    protected $connection = null;
 
    public function __construct()
    {

        try {
             $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME, DB_PORT);
             $this->connection->set_charset('utf8');
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }           
    }
 
    private function select($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement( $query , $params );
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);               
            $stmt->close();
 
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    public function insert($web, $json_data)
    {
        try {
            $stmt = $this->connection->prepare( 'INSERT INTO ws_data (web,json_data) VALUES (?,?)' );
            $stmt->bind_param('ss',$web, $json_data);
            $result = $stmt->execute();               
            $stmt->close();
 
            return $result;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }    
    
    public function getData($web)
    {
        try {
           
            $result = $this->select('SELECT json_data from ws_data WHERE web = ? ORDER BY created DESC LIMIT 1', ["s",$web]);

            return $result['0']['json_data'];
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    private function executeStatement($query = "" , $params = [])
    {
        try {
            $stmt = $this->connection->prepare( $query );
 
            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
 
            if( $params ) {
                $stmt->bind_param($params[0], $params[1]);
            }
 
            $stmt->execute();
 
            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }   
    }
}