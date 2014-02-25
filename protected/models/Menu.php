<?php

/**
 * This is the model class for table "menu".
 *
 * The followings are the available columns in table 'menu':
 * @property string $id
 * @property string $parent_id
 * @property string $label
 * @property string $url
 * @property integer $sort
 * @property string $type
 */
class Menu extends CActiveRecord
{
    
        public $parentPath;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'menu';
	}
        
	/**
	 * @return array behaviors.
	 */
	public function behaviors()
	{
		return array(
			'TreeBehavior' => array(
				'class' => 'ext.behaviors.XTreeBehavior',
				'treeLabelMethod'=> 'getTreeLabel',
				'menuUrlMethod'=> 'getMenuUrl',
			),
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sort', 'numerical', 'integerOnly'=>true),
			array('label, url', 'length', 'max'=>512),
			array('parent_id, tipe', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, label, url, sort, type, status, text, iconCls, handler, scope, window_id, module', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			  'parent' => array(self::BELONGS_TO, 'Menu', 'parent_id'),
			  'children' => array(self::HAS_MANY, 'Menu', 'parent_id', 'order' => 'sort'),
                          'childCount' => array(self::STAT, 'Menu', 'parent_id'),   
                          'menuPengguna'=>array(self::HAS_MANY, 'UserMenu', 'id_menu')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id Menu',
			'parent_id' => 'Parent',
			'label' => 'Nm Menu',
			'url' => 'Alamat Menu',
			'sort' => 'Urut Menu',
			'type' => 'Tipe',
                        'status' => 'Status',
                        'text' => 'Text',
                        'iconCls' => 'Icon Cls',
                        'handler' => 'handler',
                        'scope' => 'Scope',
                        'window_id' => 'Window Id',
                        'module' => 'Module'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_menu',$this->id_menu,true);
		$criteria->compare('parent_id',$this->parent_id,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('type',$this->type,true);
                $criteria->compare('status',$this->type,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
	/**
	 * @return string tree label
	 */
	public function getTreeLabel()
	{
		return $this->label . ':' . $this->childCount;
	}
        
        public function loadModel($id){
            return Menu::model()->findByPk($id);
        }
        
	/**
	 * @return array menu url
	 */
	public function getMenuUrl()
	{
		if(Yii::app()->controller->action->id=='adminMenu')
			return array('admin', 'id'=>$this->id);
		else
			return array('index', 'id'=>$this->url);
	}

        /**
	 * Retrieves a list of child models
	 * @param integer the id of the parent model
	 * @return CActiveDataProvider the data provider
	 */
	public function getDataProvider($id=null)
	{
		if($id===null)
			$id=$this->TreeBehavior->getRootId();
		$criteria=new CDbCriteria(array(
			'condition'=>'parent_id=:id',
			'params'=>array(':id'=>$id),
			'order'=>'label',
			'with'=>'childCount',
		));
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'pagination'=>false,
		));
	}
}