<?php
namespace Lib;

class View
{

    /**
     * 单例
     * @var null
     */
    private static $instance = null;

    /**
     * @var string
     */
    public $tplPath;

    /**
     * 模板文件
     * @var string
     */
    public $layoutName = 'default';

    /**
     * 模板中所使用的变量集合
     * @var array
     */
    private $viewVar;

    /**
     * 模板文件中使用的通用配置
     * @var array
     */
    protected $viewConf;

    public function __construct()
    {
    }

    /**
     * @return View
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $tplPath
     */
    public function setTplPath($tplPath)
    {
        $this->tplPath = $tplPath;
    }

    /**
     * @param $varName
     * @param $varValue
     */
    public function assign($varName, $varValue)
    {
        $this->viewVar[ltrim($varName,'$')] = $varValue;
    }

    public function setViewConf(array $viewConf)
    {
        $this->viewConf = $viewConf;
    }

    /**
     * @param $layoutName
     */
    public function setLayout($layoutName)
    {
        $this->layoutName = $layoutName;
    }

    /**
     * @var array
     */
    protected $headerArr;

    public function setHeader($key, $value)
    {
        $this->headerArr[$key] = $value;
    }

    /**
     * @param string $tplName
     */
    public function display($tplName = null)
    {
        if (!empty($this->headerArr)) {
            foreach ($this->headerArr as $_hk => $_hv) {
                header($_hk . ':' . $_hv);
            }
        }

        if (!empty($tplName)) {
            $tplFileName = $this->tplPath . DIRECTORY_SEPARATOR . $tplName . '.php';
        }
        if (!empty($this->viewVar)) {
            extract($this->viewVar);
        }
        $layoutFileName = $this->tplPath . DIRECTORY_SEPARATOR . '_layout' . DIRECTORY_SEPARATOR . $this->layoutName . '.php';
        if (file_exists($layoutFileName)) {
            require_once($layoutFileName);
        } else {
            echo "layout file {$layoutFileName} not exist!";
        }
        exit;
    }

    /**
     * @param $tplName
     * @return string
     */
    public function fetch($tplName)
    {
        ob_start();
        if (!empty($this->viewVar)) {
            extract($this->viewVar);
        }
        require($this->tplPath . DIRECTORY_SEPARATOR . $tplName);
        return ob_get_clean();
    }

    public function __get($var)
    {
        return $this->$var;
    }
}