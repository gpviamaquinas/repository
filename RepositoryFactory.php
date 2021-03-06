<?php

namespace gpviamaquinas\repository;

/**
 * Essa classe é o Factory dos repositórios
 */
class RepositoryFactory {

    public static function createRepository()
    {
        $connection = Connection::getInstance();

        return new Repository($connection);
    }

    public function createDatabaseRepository($requestClass)
    {
        $connection = Connection::getInstance();
        
        return new $requestClass($connection);
    }
}
