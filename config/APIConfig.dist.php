<?php
namespace config;
/**
 * This class holds the API Access information for a user who has access to it.  DO NOT STORE THIS FILE IN THE REPO
 *
 * @package default
 * @author Johnathan Pulos
 **/
class APIConfig 
{
    /**
     * The credentials of the user
     *
     * @var array
     * @access private
     **/
    private $credentials = array(
                'username'  =>  'USERNAME',
                'password'  =>  'PASSWORD'
            );
    /**
     * get the credentials for the API
     *
     * @return array
     * @access public
     * @author Johnathan Pulos
     **/
    public function getCredentials()
    {
        return $this->credentials;
    }
}
?>