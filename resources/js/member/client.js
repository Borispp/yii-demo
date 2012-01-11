jQuery(function($){
	function DiscountMembership()
	{
		var obSelf = this;
		this.add = function()
		{
			$.ajax({
				url: '/member/client/GetEventsList/',
				dataType: 'json'
			}).done(function(data) {
				var currentId = $('.item', '#membership-section').length+1;
				var selectObj = $('<select id="select-'+currentId+'"></select>');
				$.each(data.mebershipList, function(key, value) {
					selectObj
							.append($('<option>', { value : key })
							.text(value));
				});
				var item = $('<div class="item">'+
					'<a href="javascript:void(0)" class="item-delete-link">Delete</a>'+
				'</div>');
				item.prepend(selectObj);
				$('#membership-section').append(item);
				item.find('a.item-delete-link').click(obSelf.drop);
				item.find('input').attr('name', 'Discount[membership_ids]['+selectObj.val()+']');
				selectObj.change(function(){
					item.find('input').attr('name', 'Discount[membership_ids]['+$(this).val()+']');
				}).uniform();
			});
		};

		this.drop = function()
		{
			$(this).parents('.item').fadeOut('fast', function(){
				$(this).remove();
			});
		};

		this.init = function()
		{

			$('.item .item-delete-link', '#membership-section').click(obSelf.drop);
			$('#add-to-membership').click(obSelf.add);
		};
		this.init();
	}
	new DiscountMembership();
});