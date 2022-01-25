<?php

use App\Models\Auth\Role;
use Khill\Duration\Duration;



if (! function_exists('getAllMentors')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    
    function getAllMentors()
    {
        return Role::with(['users.account'])->mentors()->first();        
    }
}

if (! function_exists('getAllContributors')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    
    function getAllContributors()
    {
        return Role::with(['users.account'])->contributors()->first();        
    }
}

if (! function_exists('durationHumanize')) {
    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    
    function durationHumanize($duration)
    {
        $d = new Duration($duration);
        $ht = $d->humanize();

        if(strlen($ht) < 4) {
            return $ht;
        } else {
            return substr($ht, 0, -3);   
        } 
    }
}