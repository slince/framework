<?php
namespace DefaultApplication\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Links Model
 */
class LinksTable extends Table
{

    use TimeUpdateTrait;
    /**
     * Initialize method
     *
     * @param array $config
     *            The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('links');
        $this->displayField('name');
        $this->primaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator            
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator->add('id', 'valid', [
            'rule' => 'numeric'
            ])
            ->allowEmpty('id', 'create')
            ->requirePresence('name')
            ->notEmpty('name', '名称不得为空')
            
            ->requirePresence('src')
            ->allowEmpty('src')
            ->add('src', 'url', [
                'rule' => 'url',
                'message' => '图片地址必须是合法的url'
            ])
            ->requirePresence('href')
            ->notEmpty('href', '链接的地址不得为空')
            ->add('href', 'url', [
                'rule' => 'url',
                'message' => '链接的地址必须是合法的url'
            ])
            
            ->add('sort', 'valid', [
                'rule' => 'numeric',
                'message' => '排序编号必须是纯数字'
             ])
            ->requirePresence('sort')
            ->notEmpty('sort', '排序编号不得为空')
            ->add('status', 'valid', [
                'rule' => 'boolean',
                'message' => '状态异常'
             ])
            ->requirePresence('status')
            ->notEmpty('status', '状态不得为空');
        return $validator;
    }
    
    function getName($id)
    {
        return $this->findById($id)->select(['name'])->first()->toArray();
    }

}
