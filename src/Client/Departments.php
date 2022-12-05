<?php

namespace Alpifra\LuccaPHP\Client;

use Alpifra\LuccaPHP\BaseClient;

/**
 * Timmi Absences service manager from Lucca API
 * 
 * @see https://developers.lucca.fr/docs/lucca-legacyapi/2713ebbef0217-timmi-absences-api
 */
class Departments extends BaseClient implements ClientInterface
{
    
    /**
     * List all departments
     *
     * @param  int|null $headId
     * @param  int|null $parentId
     * @return \stdClass
     */
    public function list(int|null $headId = null, int|null $parentId = null): \stdClass
    {
        $params = [
            'paging' => [$this->getPagingOffset(), $this->getPagingLimit()],
        ];
        $headId ? $params['headId'] = $headId : null;
        $parentId ? $params['parentId'] = $parentId : null;

        $params = array_merge($this->getFields(), $params);

        return $this->initRequest()->get('/api/v3/departments', $params);
    }

    /**
     * Find a department by id
     *
     * @param  string $departmentId
     * @return \stdClass
     */
    public function find(string $departmentId): \stdClass
    {
        return $this->initRequest()->get("/api/v3/departments/{$departmentId}", $this->getFields());
    }

    /**
     * List all departments as a tree
     *
     * @return \stdClass
     */
    public function listTree(): \stdClass
    {
        return $this->initRequest()->get('/api/v3/departments/tree', $this->getFields());
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableFields(): array
    {
        $helpResponse = $this->initRequest()->get('/api/v3/departments/help');
        return $helpResponse->data?->fields;
    }

}