<?php

/**
 * Class PdoDebugger
 *
 * Emulates the PDO SQL statement in an extremely simple kind of way
 */
class PdoDebugger
{
    /**
     * Returns the emulated SQL string
     *
     * @param $raw_sql
     * @param $parameters
     * @return mixed
     */
    static public function show($raw_sql, $parameters)
    {
        $keys = array();
        $values = array();

        /*
         * Get longest keys first, sot the regex replacement doesn't
         * cut markers (ex : replace ":username" with "'joe'name"
         * if we have a param name :user )
         */
        $isNamedMarkers = false;
        if (count($parameters) && is_string(key($parameters))) {
            uksort($parameters, function($k1, $k2) {
                return strlen($k2) - strlen($k1);
            });
            $isNamedMarkers = true;
        }
        foreach ($parameters as $key => $value) {

            // check if named parameters (':param') or anonymous parameters ('?') are used
            if (is_string($key)) {
                $keys[] = '/:'.ltrim($key, ':').'/';
            } else {
                $keys[] = '/[?]/';
            }

            // bring parameter into human-readable format
            if (is_string($value)) {
                $values[] = "'" . addslashes($value) . "'";
            } elseif(is_int($value)) {
                $values[] = strval($value);
            } elseif (is_float($value)) {
                $values[] = strval($value);
            } elseif (is_array($value)) {
                $values[] = implode(',', $value);
            } elseif (is_null($value)) {
                $values[] = 'NULL';
            }
        }
        if ($isNamedMarkers) {
            return preg_replace($keys, $values, $raw_sql);
        } else {
            return preg_replace($keys, $values, $raw_sql, 1, $count);
        }
    }
}
