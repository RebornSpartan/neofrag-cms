$(function(){
	var close = function($menu, $parent){
		$menu.next('.nav').slideUp(function(){
			$parent.removeClass('active');
		});
	};

	$('.nav [data-toggle="collapse"]').each(function(){
		var $parent = $(this).parent();

		$(this).on('click', function(){
			var $menu = $(this);

			if ($parent.hasClass('active')){
				close($menu, $parent);
			}
			else {
				$menu.next('.nav').slideDown(function(){
					$parent.addClass('active');
				});

				$menu.parents('.nav').find('[data-toggle="collapse"]').each(function(){
					if ($menu[0] != $(this)[0]){
						close($(this), $(this).parent());
					}
				});
			}
		});
	});
});
