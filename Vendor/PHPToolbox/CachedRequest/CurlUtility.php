<?php
/**
 * This file is part of  PHPToolbox, a library of useful PHP code.
 *
 * PHPToolbox is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPToolbox is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * @author Johnathan Pulos <johnathan@missionaldigerati.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */
namespace PHPToolbox\CachedRequest;

/**
 * A class for handling the cURL requests
 *
 * @package default
 * @author Johnathan Pulos
 */
class CurlUtility
{
    /**
     * The status code of the request
     *
     * @var integer
     * @access public
     */
    public $responseCode = 0;
    /**
     * The last URL visited by cURL
     *
     * @var string
     * @access public
     **/
    public $lastVisitedURL = "";
    /**
     * Make a cURL Request
     *
     * @param string $url the url to request
     * @param string $method the method to use POST or GET
     * @param array $fields an array of fields to send
     * @return string
     * @access public
     * @author Johnathan Pulos
     */
    public function makeRequest($url, $method, $fields = array())
    {
        $method = strtoupper($method);
        /**
         * open connection
         *
         * @author Johnathan Pulos
         */
        $ch = curl_init();
        if ($method == 'GET') {
            $fieldsString = $this->urlify($fields);
            $url = $url . "?" . $fieldsString;
        } else {
            $fieldsString = $fields;
        }
        /**
         * Setup cURL, we start by spoofing the user agent since it is from code:
         * http://davidwalsh.name/set-user-agent-php-curl-spoof
         *
         * @author Johnathan Pulos
         */
        // curl_setopt(
        //     $ch,
        //     CURLOPT_USERAGENT,
        //     'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'
        // );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        /**
         * Follow all redirections
         *
         * @author Johnathan Pulos
         **/
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
        }
        /**
         * execute request
         *
         * @author Johnathan Pulos
         */
        $result = curl_exec($ch) or die(curl_error($ch));
        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->lastVisitedURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        /**
         * close connection
         *
         * @author Johnathan Pulos
         */
        curl_close($ch);
        return $result;
    }
    /**
     * Takes an array of fields and makes a string from them for passing in cURL
     *
     * @param array $fields the fields to urlify
     * @return string
     * @access public
     * @author Johnathan Pulos
     */
    public function urlify($fields)
    {
        $fieldsString = '';
        foreach ($fields as $key => $value) {
            $fieldsString .= $key.'='.$value.'&';
        }
        return rtrim($fieldsString, '&');
    }
}
