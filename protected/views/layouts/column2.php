<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
<div class="span3">
    <?php
        $this->widget('StatusBar'); //, array('status' => $this->status, 'id' => $this->id)
    ?>
</div>
<div class="span9">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
    </div>
<?php $this->endContent(); ?>