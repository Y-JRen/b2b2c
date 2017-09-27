<?php

namespace backend\models\search;

use common\models\PartnerIdentity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\form\PartnerForm as PartnerModel;

/**
 * Partner represents the model behind the search form of `common\models\Partner`.
 */
class Partner extends PartnerModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'contact_person', 'contact_phone', 'create_time', 'update_time', 'partner_identity'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        if($this->partner_identity) {
            $query = PartnerModel::find()->alias('a')->innerJoin(PartnerIdentity::tableName() .'  b', 'b.partner_id = a.id');
            $query->where([
                'in', 'b.identity_id', $this->partner_identity
            ]);
        } else {
            $query = PartnerModel::find()->alias('a');
        }
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.create_time' => $this->create_time,
            'a.update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.address', $this->address])
            ->andFilterWhere(['like', 'a.contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'a.contact_phone', $this->contact_phone]);
        return $dataProvider;
    }
}
