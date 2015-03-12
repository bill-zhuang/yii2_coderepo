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
        $this->_generateModelFile();
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
            $js_name = strtolower(implode('-', $this->_splitControllerName()));
            $this->_js[] = 'js/' . (($this->_module_name === '') ? '' : (strtolower($this->_module_name) . '/'))
                . $js_name . '/' . $js_name . '.js';
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
            $js_folder_path = __DIR__ . '/../web/js';
            $this->_createDirectory($js_folder_path);
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

    private function _generateModelFile()
    {
        $model_folder_path = __DIR__ . '/../'
            . ($this->_module_name === '' ? '' : ('modules/' . strtolower($this->_module_name) . '/'))
            . 'models';
        $this->_createDirectory($model_folder_path);
        //create table model
        foreach ($this->_table_names as $table_name)
        {
            $model_name = $this->_getModelNameByTableName($table_name);

            $params = [
                'module_name' => $this->_module_name,
                'model_name' => $model_name,
                'table_name' => $table_name,
                'primary_id' => $this->_getTablePrimaryID($table_name),
                'table_fields' => $this->_getTableFieldsForModel($table_name),
                'labels' => $this->_getTableLabelsForModel($table_name),
                'rules' => $this->_getTableRulesForModal($table_name),
                'controller_name' => $this->_controller_name,
            ];
            $template_path = __DIR__ . '/template/ModelTemplate.php';
            $dest_path = $model_folder_path . '/' . $model_name . '.php';

            $create_result = $this->_renderFile($template_path, $dest_path, $params);
            if ($create_result !== false)
            {
                echo 'Create Model File ' . $model_name . '.php Successfully' . PHP_EOL;
            }
            else
            {
                echo 'Create Model File ' . $model_name . '.php Failed' . PHP_EOL;
            }
        }
    }

    private function _generateControllerFile()
    {
        if ($this->_controller_name === '')
        {
            echo 'Controller Name can\'t be empty.' . PHP_EOL;
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
            'table_data' => empty($this->_table_names) ? [] : $this->_getTableInsertArrayForController($this->_table_names[0]),
            'form_element_prefix' => strtolower(implode('_', $this->_splitControllerName())),
        ];
        $template_path = __DIR__ . '/template/ControllerTemplate.php';
        $dest_path = $controller_folder_path . '/' . $this->_controller_name . 'Controller.php';

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
            $type_name = $this->_getMySQLFieldType($definition['Type']);
            $php_type = $this->_getPhpType($type_name, (stripos($definition['Type'], 'unsigned') !== false));

            $table_fields[$definition['Field']] = $php_type; //fc_weight => integer
        }

        return $table_fields;
    }

    //copy & modify from
    private function _getTableRulesForModal($table_name)
    {
        $definitions = $this->_getTableInfo($table_name);
        $table_fields = [];
        foreach ($definitions as $definition)
        {
            if ($definition['Key'] !== 'PRI')
            {
                $type_name = $this->_getMySQLFieldType($definition['Type']);
                $table_fields[$definition['Field']] = $type_name;

                //todo need update
                /*if (($definition['Null'] === 'NO') && $definition['Default'] === null)
                {
                    $types['required'][] = $type_name;
                }*/
            }
        }

        $types = [];
        foreach ($table_fields as $filed_name => $type)
        {
            switch ($type)
            {
                case 'smallint':
                case 'integer':
                case 'bigint':
                    $types['integer'][] = $filed_name;
                    break;
                case 'boolean':
                    $types['boolean'][] = $filed_name;
                    break;
                case 'float':
                case 'decimal':
                case 'money':
                    $types['number'][] = $filed_name;
                    break;
                case 'date':
                case 'time':
                case 'datetime':
                case 'timestamp':
                    $types['safe'][] = $filed_name;
                    break;
                default: // strings
                    $types['string'][] = $filed_name;
                    //todo need update
                    /*if ($column->size > 0) {
                        $lengths[$column->size][] = $filed_name;
                    } else {
                        $types['string'][] = $filed_name;
                    }*/
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }
        //todo need update
        /*foreach ($lengths as $length => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], 'string', 'max' => $length]";
        }*/
        //todo need update for unique index rule
        return $rules;
    }

    //copy & modify from vendor\yiisoft\yii2\db\mysql\Schema.php
    private function _getMySQLFieldType($type)
    {
        $mysql_data_types = $this->_getConfig('mysql_data_type.ini');
        $type_name = '';
        if (preg_match('/^(\w+)(?:\(([^\)]+)\))?/', $type, $matches))
        {
            $type = strtolower($matches[1]);
            if (isset($mysql_data_types[$type]))
            {
                $type_name = $mysql_data_types[$type];
            }

            if (!empty($matches[2]))
            {
                $values = explode(',', $matches[2]);
                if ($type === 'enum')
                {
                    ;
                }
                else
                {
                    $size = intval($values[0]);
                    $update_type = '';
                    if ($size === 1 && $type === 'bit')
                    {
                        $update_type = 'boolean';
                    }
                    elseif ($type === 'bit')
                    {
                        if ($size > 32)
                        {
                            $update_type = 'bigint';
                        }
                        elseif ($size === 32)
                        {
                            $update_type = 'integer';
                        }
                    }
                    if ($update_type !== '')
                    {
                        $type_name = $update_type;
                    }
                }
            }
        }

        return $type_name;
    }

    //copy & modify from vendor\yiisoft\yii2\db\Schema.php
    private function _getPhpType($type, $is_unsigned)
    {
        $typeMap = [
            // abstract type => php type
            'smallint' => 'integer',
            'integer' => 'integer',
            'bigint' => 'integer',
            'boolean' => 'boolean',
            'float' => 'double',
            'binary' => 'resource',
        ];
        if (isset($typeMap[$type]))
        {
            if ($type === 'bigint')
            {
                return PHP_INT_SIZE == 8 && !$is_unsigned ? 'integer' : 'string';
            }
            elseif ($type === 'integer')
            {
                return PHP_INT_SIZE == 4 && $is_unsigned ? 'string' : 'integer';
            }
            else
            {
                return $typeMap[$type];
            }
        }
        else
        {
            return 'string';
        }
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

        $db_config = $this->_getConfig('db.ini');
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
                $sql = 'SHOW FULL COLUMNS FROM ' . $table_name;
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

    private function _getConfig($config_name)
    {
        $config_path = __DIR__ . '/configs/' . $config_name;
        if (file_exists($config_path))
        {
            $config = parse_ini_file($config_path, true);
            return $config;
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