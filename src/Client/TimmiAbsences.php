<?php

namespace Alpifra\LuccaPHP\Client;

use Alpifra\LuccaPHP\BaseClient;
use Alpifra\LuccaPHP\Http\Request;

/**
 * Timmi Absences service manager from Lucca API
 * 
 * @see https://developers.lucca.fr/docs/lucca-legacyapi/2713ebbef0217-timmi-absences-api
 */
class TimmiAbsences extends BaseClient implements ClientInterface
{
    
    /**
     * List all leaves
     *
     * @param  int|array<array-key, int> $ownerId
     * @param  string|array<array-key, string> $date
     * @return \stdClass
     */
    public function list(int|array $ownerId, string|array $date = ['since', '2021-01-01']): \stdClass
    {
        $params = [
            'date' => $date,
            'leavePeriod.ownerId' => $ownerId,
            'paging' => [$this->getPagingOffset(), $this->getPagingLimit()]
        ];

        $params = array_merge($this->getFields(), $params);

        return $this->initRequest()->get('/api/v3/leaves', $params);
    }
    
    /**
     * List all leaves requests
     *
     * @return \stdClass
     */
    public function listRequests(): \stdClass
    {
        return $this->initRequest()->get('/api/v3/leaverequests', $this->getFields());
    }
    
    /**
     * Get one leave requests
     *
     * @param  int $id
     * @return \stdClass
     */
    public function getRequest(int $id): \stdClass
    {
        return $this->initRequest()->get('/api/v3/leaverequests/' . $id, $this->getFields());
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAvailableFields(): array
    {
        $helpResponse = $this->initRequest()->get('/api/v3/leaves/help');
        return $helpResponse->data?->fields;
    }

}