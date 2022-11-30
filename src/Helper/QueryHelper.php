<?php 

namespace Alpifra\LuccaPHP\Helper;

final class QueryHelper
{
    
    /**
     * Format an array of key and value relations to a valid query string
     *
     * @param  array<string, string|int|array<array-key, string>> $params
     * @return string
     */
    public static function formatQueryParameters(array $params): string
    {
        $queries = [];

        foreach ($params as $key => $value) {

            if ( is_array($value) ) {
                $value = implode(',', $value);
            }

            $queries[] = $key . '=' . urlencode($value);
        }

        return '?' . implode('&', $queries);
    }

}