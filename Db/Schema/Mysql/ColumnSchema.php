<?php
namespace Lib\Db\Schema\Mysql;

class ColumnSchema extends \Lib\Db\Schema\ColumnSchema
{
    /**
     * Extracts the PHP type from DB type.
     * @param string $dbType DB type
     */
    protected function extractType($dbType)
    {
        if (strncmp($dbType, 'enum', 4) === 0)
            $this->type = 'string';
        else if (strpos($dbType, 'float') !== false || strpos($dbType, 'double') !== false)
            $this->type = 'double';
        else if (strpos($dbType, 'bool') !== false)
            $this->type = 'boolean';
        else if (strpos($dbType, 'int') === 0 && strpos($dbType, 'unsigned') === false || preg_match('/(bit|tinyint|smallint|mediumint)/', $dbType))
            $this->type = 'integer';
        else
            $this->type = 'string';
    }

    /**
     * Extracts the default value for the column.
     * The value is typecasted to correct PHP type.
     * @param mixed $defaultValue the default value obtained from metadata
     */
    protected function extractDefault($defaultValue)
    {
        if ($this->dbType === 'timestamp' && $defaultValue === 'CURRENT_TIMESTAMP')
            $this->defaultValue = null;
        else
            parent::extractDefault($defaultValue);
    }

    /**
     * Extracts size, precision and scale information from column's DB type.
     * @param string $dbType the column's DB type
     */
    protected function extractLimit($dbType)
    {
        if (strncmp($dbType, 'enum', 4) === 0 && preg_match('/\((.*)\)/', $dbType, $matches)) {
            $values = explode(',', $matches[1]);
            $size = 0;
            foreach ($values as $value) {
                if (($n = strlen($value)) > $size)
                    $size = $n;
            }
            $this->size = $this->precision = $size - 2;
        } else
            parent::extractLimit($dbType);
    }
}