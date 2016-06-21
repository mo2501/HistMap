<?php

// src/HistoryBundle/Services/Generator.php
namespace HistoryBundle\Services;


class Generator{
    /**
    * Generates a reference
    *
    * @param int $length
    * @return string
    */
    public function generateRef($length = 10){
        
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
        
    }
    
}