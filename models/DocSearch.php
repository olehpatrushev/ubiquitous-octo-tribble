<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class DocSearch extends Model
{
    public $gender;
    public $category;
    public $age;
    public $birthDate;
    public $ageRangeStart;
    public $ageRangeEnd;

    public function rules()
    {
        return [
            [['gender'], 'in', 'range' => ['male', 'female']],
            [['category'], 'string', 'max' => 255],
            [['birthDate'], 'safe'],
            [['age'], 'integer', 'min' => 0, 'max' => 100],
            [['ageRangeStart'], 'integer', 'min' => 0, 'max' => 100],
            [['ageRangeEnd'], 'integer', 'min' => 0, 'max' => 100],
            [['ageRangeEnd'], 'compare', 'operator' => '>=', 'compareAttribute' => 'ageRangeStart']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider|false
     */
    public function search($params)
    {
        $query = Doc::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!$this->validate()) {
            return false;
        }

        $query->andFilterWhere([
            'birthDate' => $this->birthDate,
            'gender' => $this->gender,
            'category' => $this->category
        ]);

        if ($this->age !== null) {
            $query->andWhere('DATE_SUB(CURDATE(),INTERVAL :age YEAR) = `birthDate`', ['age' => $this->age]);
        }

        if ($this->ageRangeStart !== null) {
            $query->andWhere('DATE_SUB(CURDATE(),INTERVAL :ageRangeStart YEAR) >= `birthDate`', ['ageRangeStart' => $this->ageRangeStart]);
        }

        if ($this->ageRangeEnd !== null) {
            $query->andWhere('DATE_SUB(CURDATE(),INTERVAL :ageRangeEnd YEAR) <= `birthDate`', ['ageRangeEnd' => $this->ageRangeEnd]);
        }

        return $dataProvider;
    }
}
