<?php

/**
 * Created by PhpStorm.
 * User: rantenesse
 * Date: 1/20/2017
 * Time: 4:55 PM
 */
class DBControl
{
    function __construct ($db_location) {
        $this->db = $db_location;
        $this->connection = new PDO('sqlite:' . $db_location);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public $error = null;
    public $results = null;
    public $first_result = null;
    public $all_results = null;
    public $results_count = null;
    public $last_insert_id = null;

    public function query ($query_string) {

        try {
            $queryResults = $this->connection->prepare($query_string);
            $queryResults->execute();
            $this->results = $queryResults;
            $this->first_result = $queryResults->fetch();
            $this->all_results = $queryResults->fetchAll();
            $this->results_count = count($queryResults->fetchAll());
            $this->last_insert_id = $this->connection->lastInsertId();
            $this->error = null;
            return true;
        }
        catch (PDOException $ex) {
            // echo $ex->getMessage();
            $this->error = $this->connection->errorInfo()[2];
            $this->results = null;
            $this->results_count = null;
            $this->last_insert_id = null;
            return false;
        }
    }
}