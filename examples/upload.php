<?php
    include(__DIR__ . '/../vendor/autoload.php');
    
    $file_upload_controller = new \Codad5\FileHelper\FileUploader('test', "uploads/");
    $file_upload_controller2 = new \Codad5\FileHelper\FileUploader('tes', "uploads/");
    $file_upload_controller->add_ext('sql', 'pdf')
                           ->set_prefix('cool');

    echo '<pre>';   
        print_r($file_upload_controller->files) ;
        print_r($file_upload_controller2->files);
    echo '</pre>';

