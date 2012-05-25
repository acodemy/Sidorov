<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Денис
 * Date: 24.05.12
 * Time: 12:35
 * To change this template use File | Settings | File Templates.
 */
class ButtomProve extends CButtonColumn
{
    protected function initDefaultButtons()
    {
        if($this->viewButtonLabel===null)
            $this->viewButtonLabel=Yii::t('zii','View');
        if($this->updateButtonLabel===null)
            $this->updateButtonLabel=Yii::t('zii','Update');
        if($this->deleteButtonLabel===null)
            $this->deleteButtonLabel=Yii::t('zii','Delete');
        if($this->viewButtonImageUrl===null)
            $this->viewButtonImageUrl=$this->grid->baseScriptUrl.'/view.png';
        if($this->updateButtonImageUrl===null)
            $this->updateButtonImageUrl=$this->grid->baseScriptUrl.'/update.png';
        if($this->deleteButtonImageUrl===null)
            $this->deleteButtonImageUrl=$this->grid->baseScriptUrl.'/delete.png';
        if($this->deleteConfirmation===null)
            $this->deleteConfirmation=Yii::t('zii','Вы уверены, что хотите изменть оценку?');

        foreach(array('view','update','delete') as $id)
        {
            $button=array(
                'label'=>$this->{$id.'ButtonLabel'},
                'url'=>$this->{$id.'ButtonUrl'},
                'imageUrl'=>$this->{$id.'ButtonImageUrl'},
                'options'=>$this->{$id.'ButtonOptions'},
            );
            if(isset($this->buttons[$id]))
                $this->buttons[$id]=array_merge($button,$this->buttons[$id]);
            else
                $this->buttons[$id]=$button;
        }

        if(!isset($this->buttons['delete']['click']))
        {
            if(is_string($this->deleteConfirmation))
                $confirmation="if(!confirm(".CJavaScript::encode($this->deleteConfirmation).")) return false;";
            else
                $confirmation='';

            if(Yii::app()->request->enableCsrfValidation)
            {
                $csrfTokenName = Yii::app()->request->csrfTokenName;
                $csrfToken = Yii::app()->request->csrfToken;
                $csrf = "\n\t\tdata:{ '$csrfTokenName':'$csrfToken' },";
            }
            else
                $csrf = '';

            if($this->afterDelete===null)
                $this->afterDelete='function(){}';

            $this->buttons['delete']['click']=<<<EOD
function() {
	$confirmation
	var th=this;
	var afterDelete=$this->afterDelete;
	$.fn.yiiGridView.update('{$this->grid->id}', {
		type:'POST',
		url:$(this).attr('href'),$csrf
		success:function(data) {
			$.fn.yiiGridView.update('{$this->grid->id}');
			afterDelete(th,true,data);
		},
		error:function(XHR) {
			return afterDelete(th,false,XHR);
		}
	});
	return false;
}
EOD;
        }
    }
    protected function registerClientScript()
    {
        $js=array();
        foreach($this->buttons as $id=>$button)
        {
            if(isset($button['click']))
            {
                $function=CJavaScript::encode($button['click']);
                $class=preg_replace('/\s+/','.',$button['options']['class']);
                $class='prove';
                $js[]="jQuery('#{$this->grid->id} a.{$class}').live('click',$function);";
            }
        }

        if($js!==array())
            Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$this->id, implode("\n",$js));
    }

    protected function renderDataCellContent($row,$data)
    {
        $tr=array();
        ob_start();
        foreach($this->buttons as $id=>$button)
        {
            $this->renderButton($id,$button,$row,$data);
            $tr['{'.$id.'}']=ob_get_contents();
            ob_clean();
        }
        ob_end_clean();
        echo strtr($this->template,$tr);
    }

    protected function renderButton($id,$button,$row,$data)
    {
        if (isset($button['visible']) && !$this->evaluateExpression($button['visible'],array('row'=>$row,'data'=>$data)))
            return;
        $label='Approve';
        $url=isset($button['url']) ? $this->evaluateExpression($button['url'],array('data'=>$data,'row'=>$row)) : '#';
        $options=isset($button['options']) ? $button['options'] : array();
        $options['class'] = 'prove';
        if(!isset($options['title']))
            $options['title']=$label;
        if(isset($button['imageUrl']) && is_string($button['imageUrl']))
            echo CHtml::link(CHtml::image($button['imageUrl'],$label),$url,$options);
        else
            echo CHtml::link($label,$url,$options);
    }
}
