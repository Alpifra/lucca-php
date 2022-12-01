<?php 

namespace Alpifra\LuccaPHP\Client;

interface ClientInterface
{
    
    /**
     * Get all available fields fonr a route
     *
     * @return array<array-key, mixed>
     */
    public function getAvailableFields(): array;

}