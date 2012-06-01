<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('addcoauthors'); ?>
</div>


<div class='form'>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'enableAjaxValidation' => false,
    )); ?>

    <?php
    echo $form->errorSummary($article); ?>

    <div class="rows">
        <dl class="dl-horizontal article-data" style='text-overflow: clip;'>
            <dt><?php echo $article->getAttributeLabel('title');?></dt>
            <dd><?php echo $article->title; ?></dd>

            <dt><?php echo $article->getAttributeLabel('description');?></dt>
            <dd><?php echo $article->description; ?></dd>

            <dt><?php echo $article->getAttributeLabel('section.name');?></dt>
            <dd><?php echo $article->section->name; ?></dd>

            <dt>Авторы</dt>
            <dd><?php echo $article->author->getFullName(); ?></dd>
            <?php
                if ($article->coauthorsCount) {
                    $coauthors = $article->coauthors;
                    foreach ($coauthors as $coauthor) {
                        echo "<dd>{$coauthor->getFullName()}</dd>\n";
                    }
                }
            ?>

            <dt>Прикреплённые файлы</dt>
            <?php
                foreach ($article->files as $file) {
                    echo "<dd>{$file->title}</dd>\n";
                }
            ?>

            <?php if (!empty($article->comment)) : ?>
                <dt><?php echo $article->getAttributeLabel('comment');?></dt>
                <dd><?php echo $article->comment; ?></dd>
            <?php endif; ?>
        </dl>
    </div>
    <div class="rows buttons">
        <?php echo CHtml::submitButton('Подтвердить отправку статьи', array('name' => 'submit', 'class' => 'btn btn-primary')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
