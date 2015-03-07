<?php

namespace generator;

class TemplateGenerator
{
    private $_module_name;
    private $_controller_name;
    private $_css;
    private $_js;
    private $_asset_name;
    private $_page_title;
    private $_all_batch_id;
    private $_batch_id;
    private $_table_row_data;
    private $_primary_id;
    private $_model_names;
    private $_table_names;

    private $_cache_table_info;

    public function __construct()
    {
        //module
        $this->_module_name = 'Test';
        //controller
        $this->_controller_name = 'DefaultTest';
        //asset
        $this->_css = [
            'test.css'
        ];
        $this->_js = [
            'test.js',
        ];
        //view
        $this->_asset_name = $this->_controller_name;
        $this->_page_title = 'DefaultTest';
        $this->_all_batch_id = '';
        $this->_batch_id = '';
        $this->_table_row_data = [
            'name' => 'name',
            'weight' => 'weight',
            'create time' => 'create time',
            'update time' => 'update time',
        ];
        //tables
        $this->_table_names = [
            'finance_category',
        ];
        $this->_primary_id = $this->_getTablePrimaryID($this->_table_names[0]);
        //models
        $this->_model_names = array_map([$this, '_getModelNameByTableName'], $this->_table_names);

        //cache
        $this->_cache_table_info = [];
    }

    public function generate()
    {
        $this->_generateControllerFile();
        $this->_generateJsFile();
        $this->_generateViewFile();
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

    private function _generateViewFile()
    {
        $camel_name = $this->_splitControllerName();
        $folder_name = strtolower(implode('-', $camel_name));
        if ($folder_name !== '')
        {
            $view_folder_path = __DIR__ . '/../'
                . ($this->_module_name === '' ? '' : ('modules/' . strtolower($this->_module_name) . '/'))
                . 'views/' . $folder_name;
            $this->_createDirectory($view_folder_path);
            //create view file
            $template_path = __DIR__ . '/template/ViewTemplate.php';
            $dest_path = $view_folder_path . '/index.php';
            $params = [
                'module_name' => $this->_module_name,
                'controller_name' => $this->_controller_name,
                'asset_name' => $this->_asset_name,
                'page_title_name' => $this->_page_title,
                'all_batch_id' => $this->_all_batch_id,
                'batch_id' => $this->_batch_id,
                'table_row_data' => $this->_table_row_data,
                'primary_id' => $this->_primary_id,
                'form_element_prefix' => strtolower(implode('_', $camel_name)),
            ];
            $create_result = $this->_renderFile($template_path, $dest_path, $params);
            if ($create_result !== false)
            {
                echo 'Create View File index.php Successfully' . PHP_EOL;
            }
            else
            {
                echo 'Create View File index.php Failed' . PHP_EOL;
            }
        }
    }

    private function _generateJsFile()
    {
        $camel_name = $this->_splitControllerName();
        $folder_name = strtolower(implode('-', $camel_name));
        if ($folder_name !== '')
        {
            $js_folder_path = __DIR__ . '/../web/js/'
                . ($this->_module_name === '' ? '' : (strtolower($this->_module_name) . '/'))
                . $folder_name;
            $this->_createDirectory($js_folder_path);
            //create view file
            $template_path = __DIR__ . '/template/JsTemplate.php';
            $dest_path = $js_folder_path . '/' . $folder_name . '.js';
            $params = [
                'module_name' => $this->_module_name,
                'controller_name' => $this->_controller_name,
                'controller_url' => $folder_name,
                'all_batch_id' => $this->_all_batch_id,
                'batch_id' => $this->_batch_id,
                'table_row_data' => $this->_table_row_data,
                'primary_id' => $this->_primary_id,
                'form_element_prefix' => strtolower(implode('_', $camel_name)),
            ];
            $create_result = $this->_renderFile($template_path, $dest_path, $params);
            if ($create_result !== false)
            {
                echo 'Create View File index.php Successfully' . PHP_EOL;
            }
            else
            {
                echo 'Create View File index.php Failed' . PHP_EOL;
            }
        }
    }

    private function _generateControllerFile()
    {
        if ($this->_controller_name === '' || empty($this->_table_names))
        {
            echo 'Controller Name or Table Name can\'t be empty.' . PHP_EOL;
            exit;
        }
        $controller_folder_path = __DIR__ . '/../'
            . ($this->_module_name === '' ? '' : ('modules/' . strtolower($this->_module_name) . '/'))
            . 'controllers';
        $this->_createDirectory($controller_folder_path);
        //create controller
        $params = [
            'module_name' => $this->_module_name,
            'model_names' => $this->_model_names,
            'table_names' => $this->_table_names,
            'controller_name' => $this->_controller_name,
            'primary_id' => $this->_primary_id,
            'table_data' => $this->_getTableInsertArrayForController($this->_table_names[0]),
            'form_element_prefix' => strtolower(implode('_', $this->_splitControllerName())),
        ];
        $template_path = __DIR__ . '/template/ControllerTemplate.php';
        $dest_path = $controller_folder_path . '/' . $this->_controller_name . '.php';

        $create_result = $this->_renderFile($template_path, $dest_path, $params);
        if ($create_result !== false)
        {
            echo 'Create Controller File ' . $this->_controller_name . '.php Successfully' . PHP_EOL;
        }
        else
        {
            echo 'Create Controller File ' . $this->_controller_name . '.php Failed' . PHP_EOL;
        }
    }

    private function _getModelNameByTableName($table_name)
    {
        $camel_name = explode('_', $table_name);
        $model_name = implode('', array_map('ucwords', $camel_name));
        return $model_name;
    }
    
    private function _splitControllerName()
    {
        $preg_camel_word = '/([A-Z][a-z]*)/';
        $is_match = preg_match_all($preg_camel_word, $this->_controller_name, $camel_matches);
        if ($is_match)
        {
            return $camel_matches[1];
        }

        return [];
    }

    private function _getTableLabelsForModel($table_name)
    {
        $definitions = $this->_getTableInfo($table_name);
        $labels = [];
        foreach ($definitions as $definition)
        {
            $name = implode(
                ' ',
                array_map(
                    'ucwords',
                    explode(
                        '_',
                        str_replace('id', 'ID', $definition['Field'])
                    )
                )
            );
            $labels[$definition['Field']] = $name;
        }

        return $labels;
    }

    private function _getTableFieldsForModel($table_name)
    {
        $definitions = $this->_getTableInfo($table_name);
        $table_fields = [];
        foreach ($definitions as $definition)
        {
            $table_fields[$definition['Type']] = $definition['Field']; //integer => fc_weight
            $type = preg_replace('/\(.*$', '', $definition['Type']);
            switch($type)
            {
                //case ''
            }
        }

        return $table_fields;
    }

    private function _getTablePrimaryID($table_name)
    {
        $definitions = $this->_getTableInfo($table_name);
        $primary_id = '';
        foreach ($definitions as $definition)
        {
            if ($definition['Key'] === 'PRI')
            {
                $primary_id = $definition['Field'];
            }
        }

        return $primary_id;
    }

    private function _getTableInsertArrayForController($table_name)
    {
        $definitions = $this->_getTableInfo($table_name);
        $table_data = [];
        foreach ($definitions as $definition)
        {
            $table_data[$definition['Field']] = $definition['Default']; //fc_weight => 1
        }

        return $table_data;
    }

    private function _getTableInfo($table_name)
    {
        if (isset($this->_cache_table_info[$table_name]))
        {
            return $this->_cache_table_info[$table_name];
        }

        $db_config = $this->_getDBConfig();
        if ($db_config !== null)
        {
            try
            {
                $adapter = new \PDO(
                    "mysql:host={$db_config['db']['host']};dbname={$db_config['db']['dbname']}",
                    $db_config['db']['username'],
                    $db_config['db']['password'],
                    []
                );
                $sql = 'desc ' . $table_name;
                $desc = $adapter->query($sql)->fetchAll();
                //cache & return sql result
                $this->_cache_table_info[$table_name] = $desc;
                return $desc;
            }
            catch (\PDOException $e)
            {
                echo 'Connection failed: ', $e->getMessage(), PHP_EOL;
                exit;
            }
        }
    }

    private function _getDBConfig()
    {
        $db_ini_path = __DIR__ . '/configs/db.ini';
        if (file_exists($db_ini_path))
        {
            $db_config = parse_ini_file($db_ini_path, true);
            return $db_config;
        }

        return null;
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