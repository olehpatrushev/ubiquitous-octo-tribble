<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doc".
 *
 * @property int $id
 * @property string $category
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $gender
 * @property string $birthDate
 */
class Doc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category', 'firstname', 'lastname', 'email', 'gender', 'birthDate'], 'required'],
            [['gender'], 'string'],
            [['birthDate'], 'safe'],
            [['category', 'firstname', 'lastname', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'email' => 'Email',
            'gender' => 'Gender',
            'birthDate' => 'Birth Date',
        ];
    }
}
