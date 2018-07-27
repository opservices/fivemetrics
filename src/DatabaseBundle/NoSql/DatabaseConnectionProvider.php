<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/06/17
 * Time: 08:54
 */

namespace DatabaseBundle\NoSql;

use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\KernelLoader;
use InfluxDB\Client;
use InfluxDB\Database;

/**
 * Class ConnectionProvider
 * @package DatabaseBundle\NoSql
 */
class DatabaseConnectionProvider
{
    /**
     * @param string $databaseId
     * @return Database
     */
    public function getConnection(string $databaseId = null): Database
    {
        $client = KernelLoader::load()
            ->getContainer()
            ->get('algatux_influx_db.connection.app.http')
            ->getClient();

        return (is_null($databaseId))
            ? $client->selectDB('undefined')
            : $this->useDatabase($client, $databaseId);
    }

    /**
     * @param Client $client
     * @param string $databaseId
     * @return Database
     */
    protected function useDatabase(
        Client $client,
        string $databaseId
    ): Database {
        $database = $client->selectDB($databaseId);

        if (! $database->exists()) {
            throw new \InvalidArgumentException(
                'An invalid account id has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        return $database;
    }
}
