<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MyPHPMailer {
    public function __construct() {
        require 'PHPMailer/PHPMailerAutoload.php';
    }
}