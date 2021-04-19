<?php

namespace app\controllers;

use app\models\Doc;
use app\models\DocSearch;
use app\models\FilterForm;
use app\models\UploadForm;
use yii\base\BaseObject;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class DocController extends Controller
{
    public function behaviors()
    {
        $behaviours = parent::behaviors();
        $behaviours['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        return $behaviours;
    }

    protected function verbs()
    {
        return [
            'list' => ['GET'],
            'upload' => ['POST'],
        ];
    }

    public function actionUpload()
    {
        $model = new UploadForm();

        try {
            $transaction = Doc::getDb()->beginTransaction();
            $model->docFiles = UploadedFile::getInstancesByName('docFiles');
            if ($model->upload()) {
                foreach ($model->docFiles as $upload) {
                    if (($handle = fopen($upload->tempName, "r")) !== FALSE) {
                        // skip header line
                        fgetcsv($handle, 1000000, ",");
                        // start parsing data
                        while (($data = fgetcsv($handle, 1000000, ",")) !== FALSE) {
                            $num = count($data);
                            if ($num != 6) {
                                throw new \Exception('Incorrect file format');
                            }
                            $doc = new Doc();
                            $doc->category = $data[0];
                            $doc->firstname = $data[1];
                            $doc->lastname = $data[2];
                            $doc->email = $data[3];
                            $doc->gender = $data[4];
                            $doc->birthDate = $data[5];
                            if (!$doc->save()) {
                                $transaction->rollBack();
                                return [
                                    'status' => 'error',
                                    'errors' => $doc->getErrors()
                                ];
                            }
                        }
                        fclose($handle);
                    }
                }
                $transaction->commit();
                return [
                    'status' => 'success'
                ];
            } else {
                $transaction->rollBack();
                return [
                    'status' => 'error',
                    'errors' => $model->getErrors()
                ];
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'status' => 'error',
                'errors' => [
                    $e->getMessage()
                ]
            ];
        }
    }

    public function actionList()
    {
        $searchModel = new DocSearch();
        if ($dataProvider = $searchModel->search(\Yii::$app->request->get())) {
            return [
                'status' => 'success',
                'data' => $dataProvider->models
            ];
        } else {
            return [
                'status' => 'error',
                'errors' => $searchModel->getErrors()
            ];
        }
    }
}