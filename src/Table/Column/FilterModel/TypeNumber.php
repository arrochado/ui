<?php

declare(strict_types=1);

namespace atk4\ui\Table\Column\FilterModel;

use atk4\ui\Table\Column;

class TypeNumber extends Column\FilterModel
{
    public function init(): void
    {
        parent::init();

        $this->op->values = [
            '=' => '=',
            '!=' => '!=',
            '<' => '<',
            '<=' => '< or equal',
            '>' => '>',
            '>=' => '> or equal',
            'between' => 'Between',
        ];
        $this->op->default = '=';

        $this->value->ui['form'] = [\atk4\ui\Form\Control\Line::class, 'inputType' => 'number'];
        $this->addField('range', ['ui' => ['caption' => '', 'form' => [\atk4\ui\Form\Control\Line::class, 'inputType' => 'number']]]);
    }

    public function setConditionForModel($m)
    {
        $filter = $this->recallData();
        if (isset($filter['id'])) {
            switch ($filter['op']) {
                case 'between':
                    $m->addCondition(
                        $m->expr('[field] between [value] and [range]', ['field' => $m->getField($filter['name']), 'value' => $filter['value'], 'range' => $filter['range']])
                    );

                    break;
                default:
                    $m->addCondition($filter['name'], $filter['op'], $filter['value']);
            }
        }

        return $m;
    }

    public function getFormDisplayRules()
    {
        return [
            'range' => ['op' => 'isExactly[between]'],
        ];
    }
}