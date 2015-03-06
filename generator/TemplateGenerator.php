<?php

namespace generator;

class TemplateGenerator
{
    private $_module_name;
    private $_controller_name;
    private $_css;
    private $_js;

    public function __construct()
    {
        $this->_module_name = 'Test';
        $this->_controller_name = 'Test';
        $this->_css = [
            'test.css'
        ];
        $this->_js = [
            'test.js',
        ];
    }

    public function generate()
    {
        $this->_generateModuleFile();
        $this->_generateAppAssetFile();
    }

    private function _generateAppAssetFile()
    {
        if ($this->_module_name !== '')
        {
            $asset_module_folder_path = __DIR__ . '/../assets/' . $this->_module_name;
            $this->_createDirectory($asset_module_folder_path);
        }

        if ($this->_controller_name === '')
        {
            echo 'Controller Name can\'t be empty!' . PHP_EOL;
            exit;
        }
        else
        {
            $template_path = __DIR__ . '/template/AppAssetTemplate.php';
            $dest_path = __DIR__ . '/../assets/' . (($this->_module_name === '') ? '' : ($this->_module_name . '/') )
                . 'AppAsset' . $this->_controller_name . '.php';
            $params = [
                'module_name' => $this->_module_name,
                'controller_name' => $this->_controller_name,
                'css' => $this->_css,
                'js' => $this->_js,
            ];
            $create_result = $this->_renderFile($template_path, $dest_path, $params);
            if ($create_result !== false)
            {
                echo 'Create File AppAsset' . $this->_controller_name . '.php Successfully' . PHP_EOL;
            }
            else
            {
                echo 'Create File AppAsset' . $this->_controller_name . '.php Failed' . PHP_EOL;
            }
        }
    }

    private function _generateModuleFile()
    {
        if ($this->_module_name !== '')
        {
            //create folder is not exist
            $module_path = __DIR__ . '/../modules/' . strtolower($this->_module_name);
            $create_folder_path = [
                $module_path,
                $module_path . '/controllers',
                $module_path . '/models',
                $module_path . '/views',
                $module_path . '/views/layouts',
            ];
            foreach ($create_folder_path as $dir_path)
            {
                $this->_createDirectory($dir_path);
            }

            //create Module.php file
            $template_path = __DIR__ . '/template/ModuleTemplate.php';
            $dest_path = $module_path . '/' . $this->_module_name . 'Module.php';
            $params = [
                'module_name' => $this->_module_name,
            ];
            $create_result = $this->_renderFile($template_path, $dest_path, $params);
            if ($create_result !== false)
            {
                echo 'Create File ' . $this->_module_name . 'Module.php Successfully' . PHP_EOL;
            }
            else
            {
                echo 'Create File ' . $this->_module_name . 'Module.php Failed' . PHP_EOL;
            }
        }
    }

    private function _createDirectory($dir_path)
    {
        if (!file_exists($dir_path) && !is_dir($dir_path))
        {
            $ret = mkdir($dir_path, 0777, true);
            if ($ret)
            {
                echo 'Create Directory ' . $dir_path . ' Successfully' . PHP_EOL;
            }
            else
            {
                echo 'Create Directory ' . $dir_path . ' Failed' . PHP_EOL;
            }
        }
    }

    private function _renderFile($template_path, $dest_path, array $params)
    {
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require($template_path);
        $template_content = ob_get_clean();
        return file_put_contents($dest_path, $template_content);
    }

}

$generator = new TemplateGenerator();
$generator->generate();