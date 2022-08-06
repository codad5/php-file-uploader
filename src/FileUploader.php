<?php
namespace Codad5\FileHelper;

class FileUploader{
    public $original_files;
    public $files;
    public $upload_path;
    protected $prefix = "";
    protected $allowed_ext = [];
    protected $max_size = null;
    protected $min_size = 0;
    protected $report_file_error = true;
    protected $report_size_error = true;
    public function __construct(
        String $name,
        String $upload_path = 'uploads/'
    ){
        if(!isset($_FILES[$name])) throw new \Exception("$name not an index in the super global \$_FILES");
        $this->original_files = $_FILES[$name];
        $this->upload_path = $upload_path;
        $this->__arrange_file_array();


    }

    protected function __arrange_file_array()
    {
        $img = [];
        $this->files = $this->original_files;
        if(!is_array($this->files['name'])){
            if($this->files['name'] != []) {
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
        for ($i=0; $i < count($this->files['name']); $i++) { 
            //making sure the extension is allowed
            if(!in_array($this->get_ext($this->files['name'][$i]), $this->allowed_ext) && count( $this->allowed_ext) > 0){
                continue;
            }
            if($this->files['error'][$i] && $this->report_file_error){
                throw new \ErrorException("file with name $this->files['name'][$i] has an error");
            }
            # For max size validation
            if($this->files['size'][$i] > $this->max_size && !is_null($this->max_size)) throw new \LengthException("max file size of $this->max_size exceeded on file with name '$this->prefix.$this->files['name'][$i]'");
            # For min size validation
            if($this->files['size'][$i] < $this->min_size && !is_null($this->min_size)) throw new \LengthException("min file size of $this->max_size exceeded on file with name '$this->prefix.$this->files['name'][$i]'");
            $img[$i] = ['name' => $this->prefix.$this->files['name'][$i] ?? '', 'type' => $this->files['type'][$i] ?? '', 'tmp_name' =>  $this->files['tmp_name'][$i] ?? '', 'error' => $this->files['error'][$i] ?? 0, 'size' => $this->files['size'][$i]  ?? 0];
        }
        $this->files = array_values($img);
        return $this;

    }

    public function add_file(Array $file)
    {
        $this->original_files[] = $file;
        $this->__arrange_file_array();
        return $this;
    }

    protected function get_ext($file_name)
    {
        $file_ext = explode('.', $file_name);
        return strtolower(end($file_ext));

    }

    public function add_ext(String ...$ext){
        foreach ($ext as $key => $value) {
            $this->allowed_ext[] = $value;
        }
        $this->__arrange_file_array();
        return $this;
    }

    public function set_prefix(String $prefix){
        $this->prefix = $prefix;
        return $this->__arrange_file_array();
        
    }
    public function set_sizes(int $max = null ,int $min = 0){
        $this->max_size = $max;
        $this->min_size = $min;
        return $this->__arrange_file_array();
    }

    public function set_reporting($file_error, $size_error){
        $this->report_file_error = $file_error;
        $this->report_size_error = $size_error;
        return $this->__arrange_file_array();
    }

}