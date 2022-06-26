<?php

if(!defined( 'ABSPATH')){
    exit;
}

/**
 * Class Time_Utils
 *
 * @author Jonas
 */
class Time_Utils
{
    /**
     * Return readable date
     * @param int $timestamp
     * @return string
     * @since 0.1
     */
    static function format_date(int $timestamp):string
    {
        return date_i18n( wc_date_format(), $timestamp );
    }

    /**
     * Convert an UTC timestamp to site's timezone
     *
     * @since 0.1
     * @return int valid PHP timestamp int
     * @return int valid PHP timestamp int
     */
    public static function convert_timestamp_to_wp_timezone( int $timestamp ):int {

        try {
            // Get datetime object from unix timestamp
            $datetime = new DateTime( "@{$timestamp}", new DateTimeZone( 'UTC' ) );

            // Set the timezone to the site timezone
            $datetime->setTimezone( new DateTimeZone( self::get_wp_timezone_string() ) );

            // Return the unix timestamp adjusted to reflect the site's timezone
            return $timestamp + $datetime->getOffset();

        } catch ( Exception $e ) {
            global $woocommerce_shipping_date;

            // Log error
            $woocommerce_shipping_date->log( $e->getMessage() );
            return 0;
        }
    }

    /**
     * Returns the timezone string for a site, even if it's set to a UTC offset
     *
     * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
     *
     * @since 0.1
     * @return string valid PHP timezone string
     */
    public static function get_wp_timezone_string() {

        // If site timezone string exists, return it
        if ( $timezone = get_option( 'timezone_string' ) ) {
            return $timezone;
        }

        // Get UTC offset, if it isn't set then return UTC
        if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
            return 'UTC';
        }

        // Adjust UTC offset from hours to seconds
        $utc_offset *= 3600;

        // Attempt to guess the timezone string from the UTC offset
        $timezone = timezone_name_from_abbr( '', $utc_offset );

        // Last try, guess timezone string manually
        if ( false === $timezone ) {

            $is_dst = date( 'I' );

            foreach ( timezone_abbreviations_list() as $abbr ) {
                foreach ( $abbr as $city ) {
                    if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset && ! empty( $city['timezone_id'] ) ) {
                        return $city['timezone_id'];
                    }
                }
            }
        }

        // Fallback to UTC offset
        return $utc_offset / 3600;
    }
}