<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $docFiles;

    public function rules()
    {
        return [
            [['docFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'txt, csv', 'maxFiles' => 10, 'maxSize' => 1024 * 1024 * 10],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
//            foreach ($this->imageFiles as $file) {
//                $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
//            }
            return true;
        } else {
            return false;
        }
    }
}
