<?php
namespace Codad5\FileHelper;

class FileUploader{
    /**
     * This is a refrence to the file upload at start and not to be redeclared
     */
    private $original_files;
    /**
     * This is a copy of the $original_files
     */
    protected $files;
    /**
     * This is the path  for which the file will be uploaded to 
     */
    protected $upload_path;
    /**
     * This is the array that carrys all the path to the file been uploaded
     */
    protected $uploaded_files = [];
    /**
     * This is the prefix to be added to the beginning of your file
     */
    protected $prefix = "";
    /**
     * This is an array of allowed extension and ignored when empty
     */
    protected $allowed_ext = [];
    /**
     * maximum file size allowed
     */
    protected $max_size = null;
    /**
     * minimum file size allowed
     */
    protected $min_size = 0;
    /**
     * if true reports and throw error on file type based on allowed extension
     */
    protected $report_type_error = true;
    /**
     * report file error if error found 
     */
    protected $report_file_error = true;
    /**
     * report size error if error exceeded or not met with 
     */
    protected $report_size_error = true;
    /**
     * @param String $name - This is the name set to the file input during http POST request same as the name given to $_FILES
     * @param String $upload_path - This is the path the file to be uploaded to
     */
    public function __construct(
        String $name,
        String $upload_path = 'uploads/'
    ){
        if(!isset($_FILES[$name])) throw new \Exception("$name not an index in the super global \$_FILES");
        $this->original_files = $_FILES[$name];
        $this->upload_path = $upload_path;
        $this->__arrange_file_array();


    }
    /**
     * This is the method use to get all the file 
     */
    public function files()
    {
        return $this->files;
    }
    /**
     * This is a method use to get all the uploaded path
     */
    public function get_uploads()
    {
        return $this->uploaded_files;
    }
    /**
     * This is the method use for adding allowed extension
     * @param String $ext - This is list of all the allowed extension
     */
    public function add_ext(string...$ext)
    {
        foreach ($ext as $key => $value) {
            $this->allowed_ext[] = $value;
        }
        $this->__arrange_file_array();
        return $this;
    }
    /**
     * This is a method use to set prefix for your files
     * @param String $prefix - This is the prefix to  be added
     */
    public function set_prefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this->__arrange_file_array();

    }
    /**
     * To set maximum and minimum file sizes
     * @param Integer $max - The maximum file size allowed
     * @param Integer $min - The minimum file size allowed
     */
    public function set_sizes(int $max = null, int $min = 0)
    {
        $this->max_size = $max;
        $this->min_size = $min;
        return $this->__arrange_file_array();
    }
    /**
     * This is use to turn on and off reporting of the upload process
     * @param bool $file_error - to report error imbound in file from request if `true` else ignore
     * @param bool $size_error - TO report size related error if `true` else ignore
     * @param bool $type_error - To report error related to file type if `true` else ignore
     */

    public function set_reporting(bool $file_error, bool $size_error, bool $type_error)
    {
        $this->report_file_error = $file_error;
        $this->report_size_error = $size_error;
        $this->report_type_error = $type_error;
        return $this->__arrange_file_array();
    }
    /**
     * This is a method use to get the extension of a file 
     * @param String $file_name - This is the file of which extension is needed
     */
    public static function get_ext($file_name)
    {
        $file_ext = explode('.', $file_name);
        return strtolower(end($file_ext));

    }
    /**
     * This is a method used to create the folder path if not found 
     */
    protected function path_exist()
    {
        $exist = file_exists($this->upload_path);
        if($exist){
            return $exist;
        }
        if (!$this->upload_path && $this->report_path_error){

            throw new \Exception("$this->upload_path already exist", 1);
        }
        return mkdir($this->upload_path);

    }

    /**
     * This is the a method use to catch error and arrange $files array 
     */
    protected function __arrange_file_array()
    {
        $img = [];
        $this->files = $this->original_files;
        # for one file upload
        if(!is_array($this->files['name'])){
            if($this->files['name'] != []) {
                //making sure the extension is allowed
                if(empty($this->files['name']) && $this->files['size'] < 1) throw new \Exception("Invalid File Type : Suspect No file Uploaded");
                if (!in_array($this->get_ext($this->files['name']), $this->allowed_ext) && count($this->allowed_ext) > 0 && $this->report_type_error) {
                    throw new \OutOfBoundsException("$this->get_ext($this->files['name']) is an invalid file type");
                }
                if ($this->files['error'] && $this->report_file_error) {
                    throw new \ErrorException("file with name ".$this->files['name']." has an error");
                }
                # For max size validation
                if ($this->files['size'] > $this->max_size && !is_null($this->max_size)) throw new \LengthException("max file size of $this->max_size exceeded on file with name '$this->prefix.$this->files['name']'");
                # For min size validation
                if ($this->files['size'] < $this->min_size && !is_null($this->min_size)) throw new \LengthException("min file size of $this->max_size exceeded on file with name '$this->prefix.$this->files['name']'");
                $img[] = $this->files;
                $this->files = $img;
                return $this;
            };
        }
        if(empty($this->files['name'][0])){
            $img[] = [
                'name' => null,
                'type' => null,
                'tmp_name' => null,
                'error' => 4,
                'size' => 0
            ];
            $this->files = $img;
            return $this;
        }
        // for multiple file Upload
        for ($i=0; $i < count($this->files['name']); $i++) { 
            //making sure the extension is allowed
            if(!in_array($this->get_ext($this->files['name'][$i]), $this->allowed_ext) && count( $this->allowed_ext) > 0 && $this->report_type_error){
                if($this->report_type_error) throw new \OutOfBoundsException($this->get_ext($this->files['name'][$i])." is an invalid file type");
                continue;
            }
            if($this->files['error'][$i] && $this->report_file_error){
                throw new \ErrorException("file with name $this->files['name'][$i] has an error");
            }
            # For max size validation
            if($this->files['size'][$i] > $this->max_size && !is_null($this->max_size)) throw new \LengthException("max file size of $this->max_size exceeded on file with name '".$this->prefix . $this->files['name'][$i]."'");
            # For min size validation
            if($this->files['size'][$i] < $this->min_size && $this->min_size) throw new \LengthException("min file size of $this->max_size not reached on file with name '". $this->prefix . $this->files['name'][$i] . "'");
            $img[$i] = ['name' => $this->prefix.$this->files['name'][$i] ?? '', 'type' => $this->files['type'][$i] ?? '', 'tmp_name' =>  $this->files['tmp_name'][$i] ?? '', 'error' => $this->files['error'][$i] ?? 0, 'size' => $this->files['size'][$i]  ?? 0];
        }
        $this->files = array_values($img);
        return $this;

    }
    /**
     * This is the method that move all the files to the path required
     */
    public function move_files(){
        $this->__arrange_file_array();
        $this->path_exist();
        for ($i = 0; $i < count($this->files); $i++) {
            $this->uploaded_files[$i]['uploaded_path'] = "$this->upload_path".$this->files[$i]['name'];
            $this->uploaded_files[$i]['name'] = $this->files[$i]['name'];
            $this->uploaded_files[$i]['size'] = $this->files[$i]['size'];
            $this->uploaded_files[$i]['type'] = $this->files[$i]['type'];
            $this->uploaded_files[$i]['ext'] = $this->get_ext($this->files[$i]['name']);
            move_uploaded_file($this->files[$i]['tmp_name'], "$this->upload_path" . $this->files[$i]['name']);
        }
        return $this;
        


    }
    
    
    

}