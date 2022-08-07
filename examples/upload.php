<?php
    include(__DIR__ . '/../vendor/autoload.php');
    
    $file_upload_controller = new \Codad5\FileHelper\FileUploader('test', "uploads/");
    $file_upload_controller2 = new \Codad5\FileHelper\FileUploader('tes', "uploads/");
    $uploaded_file_array = $file_upload_controller
    ->set_reporting(false, false, false)
    ->add_ext('sql', 'pdf')
    ->set_prefix('notcool')
    ->move_files()
    ->get_uploads();

    echo '<pre>';   
        print_r($file_upload_controller->files()) ;
        print_r($file_upload_controller2->files());
        print_r($uploaded_file_array);
    echo '</pre>';

