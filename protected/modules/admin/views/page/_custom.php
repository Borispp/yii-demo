<div class="g12">
	<div class="fr">
		<?php echo YsaHtml::link('Add Field', '#', array('class' => 'btn green', 'id' => 'tab-custom-add-field', 'data-id' => $entry->id)); ?>
	</div>
	<div class="clearfix"></div>
</div>

<div id="tab-custom-fields" class="g12" data-id="<?php echo $entry->id; ?>">
	<?php foreach ($entry->custom as $field) : ?>
		<?php $this->renderPartial('_customField', array(
			'field' => $field,
		)); ?>
	<?php endforeach; ?>
</div>

<div class="clearfix"></div>



<script type="text/javascript">
$(function(){
	var fields = $('#tab-custom-fields');	
	var row;
	
	$('#tab-custom-add-field').click(function(e){
		e.preventDefault();
		var link = $(this);
		$.post(_admin_url + '/page/addCustomField', {
			id:link.data('id')
		}, function(data){
			if (data.success) {
				row = $(data.html);
				fields.append(row);
				row.find('input, select').uniform();
				
				_init_uploader(row.find('.load'));
				
			} else {
				$.alert(data.error);
			}
		}, 'json');
	});
	
	fields.find('a.save').live('click', function(e){
		e.preventDefault();
		var link = $(this);
		var section = link.parent().parent();
		
		$.post(_admin_url + '/page/saveCustomField', {
			id:link.data('id'),
			name:section.find('input[type=text]').val(),
			value:section.find('textarea').val()
		}, function(data){
			if (data.success) {
				section.effect('highlight')
			} else {
				$.alert(data.msg);
			}
		}, 'json');
	});
	
	fields.find('a.del').live('click', function(e){
		e.preventDefault();
		var link = $(this);
		var section = link.parent().parent();
		$.confirm('Are you sure you want to delete the custom field?', function(){
			$.post(_admin_url + '/page/deleteCustomField', {
				id:link.data('id')
			}, function(data){
				if (data.success) {
					section.fadeOut('fast', function(){
						$(this).remove();
					})
				} else {
					$.alert(data.msg);
				}
			}, 'json');
		});
	});
	
	fields.find('a.delete-image').live('click', function(e){
		e.preventDefault();
		var link = $(this);
		var section = link.parent().parent();
		$.confirm('Are you sure you want to delete the custom field image?', function(){
			$.post(_admin_url + '/page/deleteCustomFieldImage', {
				id:link.data('id')
			}, function(data){
				if (data.success) {
					section.find('.image').fadeOut('fast', function(){
						$(this).html(data.html).fadeIn('fast');
						_init_uploader(section.find('.load'));
					});
				} else {
					$.alert(data.msg);
				}
			}, 'json');
		});
	});
	
	fields.find('.image .load').each(function(){
		_init_uploader($(this));
	});

	//use jQuery UI sortable plugin for the widget sortable function
	fields.sortable({
		items: fields.find('section.custom-field'),
		containment: fields,
		opacity: 0.8,
//		distance: 5,
		handle: 'a.sort',
		forceHelperSize: true,
		placeholder: 'custom-placeholder g6',
		forcePlaceholderSize: true,
		zIndex: 10000,
		update:function(){

			var order = [];
			fields.children('section').each(function(idx, elm) {
				order.push(elm.id.split('_')[1])
			});
			
			$.post(_admin_url + '/page/sortCustomFields/id/' + fields.data('id'), {'order[]':order}, function(data){
				if (data.success) {
					fields.effect('highlight');
				} else {
					$.alert(data.msg);
				}
			}, 'json');
		}
	});
	
	function _init_uploader(section) {

		var container = section.parent();
		var browse = section.find('a');
	
		var uploader = new plupload.Uploader({
			runtimes : 'gears,html5,html4,browserplus',
			browse_button : 'photo-upload-browse',
			container : 'photo-upload-container',
			max_file_size : '10mb',
			filters : [
				{title : "Image files", extensions : "jpg,jpeg,gif,png,jpeg"},
			],
			url:_admin_url + '/page/loadCustomImage/id/' + browse.data('id'),
			container:container.attr('id'),
			browse_button:browse.attr('id'),
			multi_selection:false
		});
		uploader.init();
		uploader.bind('Error', function(up, err) {
			browse.show();
			$.alert(err.message + ' Please reload the page and try again.');
			up.refresh();
		});
		uploader.bind('FilesAdded', function(up){
			browse.hide();
			up.start();
		});

		uploader.bind('FileUploaded', function(up, file, response) {
			browse.show();
			var data = $.parseJSON(response.response);
			if (data.success) {
				container.html(data.html);
			} else {
				$.alert(data.msg);
			}
		});

		return uploader;
	}

});
</script>