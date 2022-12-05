<?php 

namespace Alpifra\LuccaPHP\Client;

use Alpifra\LuccaPHP\BaseClient;


/**
 * User service manager from Lucca API
 * 
 * @see https://developers.lucca.fr/docs/lucca-legacyapi/011f7e77fd583-list-users
 */
class Users extends BaseClient implements ClientInterface
{

    /**
     * List all leaves
     *
     * @param  int|array<array-key, int> $ownerId
     * @param  string|array<array-key, string> $date
     * @return \stdClass
     */
    public function list(): \stdClass
    {
        return $this->initRequest()->get('/api/v3/users', $this->getFields());
    }

    /**
     * Find a user by id
     *
     * @param  string $leaveId
     * @return \stdClass
     */
    public function find($userId): \stdClass
    {
        return $this->initRequest()->get("/api/v3/users/{$userId}", $this->getFields());
    }
        
    /**
     * {@inheritdoc}
     */
    public function getAvailableFields(): array
    {
        $helpResponse = $this->initRequest()->get('/api/v3/users/help');
        return $helpResponse->data?->fields;
    }

}
