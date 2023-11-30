<?php


namespace app\modules\pricer\models;


use Yii;
use yii\validators\Validator;
use dastanaron\translit\Translit;
use app\modules\pricer\core\Parse\ParseController;
use app\modules\pricer\core\Loaders\LoadMethodFactory;
use app\modules\pricer\core\Readers\ReadMethodFactory;
class Price extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pricer_price';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shipper_id','file_id','product_type'], 'integer'],
            [['active','weight'], 'integer'],
            [['settings'],'string'],
            [['fields'],'string'],
            [['name'], 'string', 'max' => 255],
            [['updated'], 'safe']
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'shipper_id' => 'Shipper ID',
            'product_type'=>'Product Type',
            'file_id' => 'File ID',
            'name' => 'Name',
            'updated' => 'Updated',
            'active' => 'Active'
        ];
    }
    public static function getLoadMethods()
    {
        return [
            'url'=>'По прямому URL',
            'email'=>'По электронной почте',
        ];
    }
    public static function getReadMethods()
    {
        return [
            'xml'=>'XML-парсинг',
            'csv'=>'CSV-парсинг',
            'xls'=>'XLS',
            'xlsx'=>'XLSX',
        ];
    }
    public function getFilename()
    {
        $translit = new Translit();
        return $translit->translit($this->name, true, 'ru-en');
    }

    public function getShipper()
    {
        return $this->hasOne(Shipper::class,['id'=>'shipper_id']);
    }
    public function getUpload()
    {
        return $this->hasOne(Upload::class,['id'=>'file_id']);
    }


    public function getPriceSettings($param=NULL)
    {
        $settings=[];
        if(is_array($this->settings)) $settings=$this->settings;
        else $settings=unserialize($this->settings);
        if(isset($param)&&isset($settings[$param])) return $settings[$param];
        elseif(!isset($param)) return $settings;
    }
    public function getLoadMethod()
    {
        return $this->priceSettings['load_method'];
    }
    public function getLoadMethodSet()
    {
        return $this->priceSettings['load_method_settings'][$this->loadMethod.'_method'];
    }
    public function getReadMethod()
    {
        return $this->priceSettings['read_method'];
    }
    public function getReadMethodSet()
    {
        return $this->priceSettings['read_method_settings'][$this->readMethod.'_method'];
    }
    public function afterFind()
    {
        if(!empty($this->settings)) $this->settings=unserialize($this->settings);
        if(!empty($this->fields)) $this->fields=unserialize($this->fields);
        parent::afterFind();
    }

    public function uploadFile(array $options=[])
    {
        $load_method_object=LoadMethodFactory::create($this->loadMethod,$this->loadMethodSet);
        if(!empty($options['ignoreExpire'])||$this->checkExpired()) {
            $result = $load_method_object->load();
            if (!empty($result['upload'])) {
                $this->link('upload', $result['upload']);
                Yii::info("Загружаем файл " . $result['upload']->file . " для " . $this->name, "pricer");
                $this->updated = date("Y-m-d H:i:s");
                $this->save();
            }
            return $result;
        }
        return ['upload'=>$this->upload];
    }
    public function readFile(array $options=[])
    {
        $read_method_object=ReadMethodFactory::create($this->readMethod,$this->readMethodSet);
        $file=$this->upload;
        $result = $read_method_object->read($file,$options);
        return $result;
    }
    public function process(array $options=[]){
        $this->uploadFile($options);
        return $this->readFile($options);
    }
    public function checkExpired()
    {
        $price_timeout=$this->getPriceSettings('time_period')?:60;
        $price_updated_ts=strtotime((string)$this->updated);
        $current_time_ts=time();
        $need_updated=FALSE;
        if($current_time_ts>$price_updated_ts+$price_timeout*60){
            /* Прайс нуждается в обновлении */
            $need_updated=TRUE;
        }
        $check_if_file_exists=($upload=$this->upload)&&file_exists($upload->file);
        if(!$check_if_file_exists) {
            $need_updated=TRUE;
        }
        return $need_updated;
    }
    public function parseRows(array $rows=[]){
        static $parseController;
        if(empty($parseController)) {
            $dataSet=[
                'cae'=>['field'=>'{source:cae}'],
                'brand'=>['field'=>'{source:name}','rules'=>['findStr'=>['list'=>['Goodyear','Hankook','Matador']]]],
                'full_razmer'=>['field'=>'{source:name}','rules'=>['findRegexp'=>['pattern'=>'/[0-9^\/]+[\/[0-9^\s]+]*R[0-9]+/','delta'=>0]]],
                'index'=>['field'=>'{source:name}','rules'=>['findRegexp'=>['pattern'=>'/[0-9]+[A-Z]{1}/','delta'=>0]]],
                'runflat'=>['field'=>'{source:name}','rules'=>['findStr'=>['list'=>['Run Flat']]]],
                'model'=>['field'=>'{source:name}','rules'=>['excludeStr'=>['list'=>['{parsed:brand}','{parsed:full_razmer}','{parsed:index}','{parsed:runflat}']]]],
                'articul'=>['field'=>'{parsed:brand} {parsed:cae}'],
            ];
            $parseController=new ParseController($dataSet);
        }
        foreach($rows as $row){
            $result=$parseController->parseRow($row);
        }
    }
}