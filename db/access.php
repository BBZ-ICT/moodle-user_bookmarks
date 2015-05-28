<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(
 
    'block/user_bookmarks:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),
 
        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),
 
    'block/user_bookmarks:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
 
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
 
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
    
    'block/user_bookmarks:addbookmark' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_ALLOW
           
        ),
    ),
    
    'block/user_bookmarks:deletebookmark' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_ALLOW
            
        ),
    ),
    
    'block/user_bookmarks:viewbookmarks' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'read',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
           'teacher'        => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW,
            'student'        => CAP_ALLOW
           
        ),
       'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),
);
