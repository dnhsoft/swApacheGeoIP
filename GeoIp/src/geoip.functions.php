<?php

if (!function_exists('geoip_country_code_by_name_v6')) {
    function geoip_country_code_by_name_v6($gi, $name)
    {
        $country_id = geoip_country_id_by_name_v6($gi, $name);
        if ($country_id !== false) {
            return $gi->GEOIP_COUNTRY_CODES[$country_id];
        }
        return false;
    }
}

if (!function_exists('geoip_country_code_by_name')) {
    function geoip_country_code_by_name($gi, $name)
    {
        $country_id = geoip_country_id_by_name($gi, $name);
        if ($country_id !== false) {
            return $gi->GEOIP_COUNTRY_CODES[$country_id];
        }
        return false;
    }
}

if (!function_exists('geoip_country_name_by_name_v6')) {
    function geoip_country_name_by_name_v6($gi, $name)
    {
        $country_id = geoip_country_id_by_name_v6($gi, $name);
        if ($country_id !== false) {
            return $gi->GEOIP_COUNTRY_NAMES[$country_id];
        }
        return false;
    }
}

if (!function_exists('geoip_country_name_by_name')) {
    function geoip_country_name_by_name($gi, $name)
    {
        $country_id = geoip_country_id_by_name($gi, $name);
        if ($country_id !== false) {
            return $gi->GEOIP_COUNTRY_NAMES[$country_id];
        }
        return false;
    }
}