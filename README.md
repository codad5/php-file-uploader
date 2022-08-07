# FILE UPLOADER
FIle Uploader is a php package to aid fast , easy and safe file upload

## installation
```bash
coming soon
```
## Features
- Fast and easy to use with method chaining
- Good error management
- Safety management
- works for both multiple file upload and single file upload

## Usage
`index.php`
```html
<form action="./upload.php" method='post' enctype="multipart/form-data">
    <input type="file" name="test[]" multiple>
    <input value="submit" type="submit">
</form>
```
`upload.php`
```php
<?php
    include(__DIR__ . '/../vendor/autoload.php');
    
    $file_upload_controller = new Codad5\FileHelper\FileUploader('test', "uploads/");
```
`test` - the name given to the `$_FILES` array by default - for refrence `$_FILES['test`]`

`uploads/` - Upload path relative to `upload.php`


### To add allowed extension 

```php
#any other extension aid the given will give error or ignored depending on your error settings
$file_upload_controller->add_ext('svg', 'png');
```
### To set File Prefix 
```php
# for unique id can be replaced with uniqid('', true)
$file_upload_controller->set_prefix('my prefix');
```
### To set max and min size
```php
$file_upload_controller->set_sizes(10000, 20);
```
`10000` - This is the maximum file size allowed, to ignore for your `.ini` config set it to `null`
`20` - This is the minimum file size allowed default equals `0`

### Turing on/Off Error reporting 
```php
/**
 * This is use to turn on and off reporting of the upload process
 * @param bool $file_error - to report error imbound in file from request if `true` else ignore
 * @param bool $size_error - TO report size related error if `true` else ignore
 * @param bool $type_error - To report error related to file type if `true` else ignore
 */
$file_upload_controller->set_reporting(true, false, true);
```
- The first param `$file_error` is to report error imbounded in file from request as found in `$_SERVER['test']`
- The second param `$size_error` is to report size related error depending on your settings
- The Third param `$type_error` is to report error if a file is not part of the allowed file
> NOTE: If any is `false` and error is found it will ignore the file and continue upload with out the file

### Moving the file
```php

$file_upload_controller->move_files();

```
### Get Uploaded file path
```php

$upload_path = $file_upload_controller->get_uploads();

foreach ($upload_path as $key => $value) {
        # code...
        echo "This file has been uploaded to ".$value['uploaded_path']."<br/>;
    }

```
- This returns a multi-dimensional array of each array with the following key
- `uploaded_path` - The final uploaded file with path relative to the script tag `upload.php`
- `name` - The name of the file 
- `size` - The size of the file
- `type` - The file Type
- `ext` - The file extension

### Method Chaining 
```php

$file_upload_controller = new \Codad5\FileHelper\FileUploader('tes', "uploads/");

    $uploaded_file_array = $file_upload_controller
    ->set_reporting(false, false, false)
    ->add_ext('png', 'pdf')
    ->set_prefix('cool Stuff')
    ->move_files()
    ->get_uploads();

    foreach ($uploaded_file_array as $key => $value) {
        # code...
        echo "This file has been uploaded to ".$value['uploaded_path']."<br/>";
    }

```


> This documentaion will be updated in time as the project grows
> for enquire and more information [contact me here](https://twitter.com/codad5_)

Built with 💗 by [Chibueze Michael A.](https://github.com/codad5)