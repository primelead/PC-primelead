<?php

class ShopController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($translit_url)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($translit_url),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Shop;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Shop']))
		{

			$model->attributes=$_POST['Shop'];

			$model->icon=CUploadedFile::getInstance($model,'icon');
			if ($model->icon){
				$sourcePath = pathinfo($model->icon->getName());	
				$fileName = date("l-dS-of-F-Y-h-i-s-A").'.'.$sourcePath['extension'];
				$model->image = $fileName ;
			}
			if($model->save()){
			if ($model->icon){				
					//сохранить файл на сервере в каталог images/2011 под именем 
					//month-day-alias.jpg
					$file ='E:/xampp/htdocs/top-class/pokupon/images/'.$fileName;
					$model->icon->saveAs($file);
				}
				$this->redirect(array('view','id'=>$model->id));}
		}
		

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Shop']))
		{
			$model->attributes=$_POST['Shop'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}
public function post_image($id, $image, $width='150', $class='post_img'){
  if(strlen($image)>1)
   return CHtml::image(Yii::app()->getBaseUrl(true)."/images/$image",'',
      array(
        'width'=>$width,
        'class'=>$class
      )
    );
  else if (strlen($image)<1)
    return CHtml::image(Yii::app()->getBaseUrl(true)."/images/pics/home.gif",'Нет картинки',
      array(
        'width'=>$width,
        'class'=>$class
      )
    );
 }
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Shop');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Shop('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Shop']))
			$model->attributes=$_GET['Shop'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Shop the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($translit_url)
	{
		$model=Shop::model()->find('translit_url=:translit_url',array(':translit_url'=>$translit_url));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
public function loadModelName($translit_url)
	{
		$model=Shop::model()->find('translit_url=:translit_url',array(':translit_url'=>$translit_url));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	/**
	 * Performs the AJAX validation.
	 * @param Shop $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='shop-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
