<?php
 
class block_user_boomarks_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
    

        // A sample string variable with a default value.
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_user_bookmarks'));
        $mform->setDefault('config_title', get_string('blocktitle', 'block_user_bookmarks'));
        $mform->setType('config_title', PARAM_MULTILANG);        
 
    }
}