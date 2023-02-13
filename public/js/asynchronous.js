/**
 * 非同期通信処理記述 
 * 
 * 検索部分
 */

$('.search-btn').on('click', () => {
	// 検索結果を取得
	let keyword = $('#keyword').val();
	let selected_name = $('#select_company').val();
	let min_price = $('#min_price').val();
	let max_price = $('#max_price').val();
	let min_stock = $('#min_stock').val();
	let max_stock = $('#max_stock').val();
	if (!keyword && !selected_name && !min_price && !max_price && !min_stock && !max_stock) {
		return false;
	}

	var url = location.origin;
	$.ajax({
		type: 'GET',
		url: url,
		data: {
			keyword: keyword,
			company_id: selected_name,
			min_price: min_price,
			max_price: max_price,
			min_stock: min_stock,
			max_stock: max_stock,
		},
		dataType: 'json'
	})
		.done(function(data) {
			console.log('success');
			const tbody = $('#ajax_table');
			tbody.empty();

			for (const i of data.data) {
				console.log(i.img_path);
				const tr = $('<tr></tr>');
				tbody.append(tr);

				let td_1 = $('<td></td>');
				td_1.text(i.id);
				tr.append(td_1);

				let td_2 = $('<td><img src=""></img></td>');
				td_2.text(i.img_path);
				tr.append(td_2);

				let td_3 = $('<td></td>');
				td_3.text(i.product_name);
				tr.append(td_3);

				let td_4 = $('<td></td>');
				td_4.text(i.price);
				tr.append(td_4);

				let td_5 = $('<td></td>');
				td_5.text(i.stock);
				tr.append(td_5);

				let td_6 = $('<td></td>');
				td_6.text(i.company_name);
				tr.append(td_6);
			}

		})
		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(keyword);
			console.log('jqXHR          : ' + jqXHR.status); // HTTPステータスが取得
			console.log('textStatus     : ' + textStatus); // タイムアウト、パースエラー
			console.log('errorThrown    : ' + errorThrown.message);
			console.log('URL            : ' + url);
		});
});

/**
 * 
 * 商品削除非同期処理
 */

// CSRF対策
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function() {

	$('.del-btn').on('click', (event) => {
		let delete_confirm = confirm('削除してよろしいですか？');
		// event.preventDefault();

		if (delete_confirm == true) {
			let click_ele = $(event.target);
			let product_id = click_ele.data("product-id");
			console.log(product_id);
		
			$.ajax({
				type: 'DELETE',
				url: '/product/delete/' + product_id,
				dataType: 'json',
				data: {
					'product_id': product_id, '_method': 'DELETE'
				},
			})
			.done(function() {
				console.log('削除成功');
				click_ele.parent().parent().parent().remove();
			})
			.fail(function(jqXHR, textStatus, errorThrown, url) {
				alert('削除できてません');
				console.log('削除できてません');
				console.log('jqXHR          : ' + jqXHR.status); // HTTPステータスが取得
				console.log('textStatus     : ' + textStatus); // タイムアウト、パースエラー
				console.log('errorThrown    : ' + errorThrown.message);
				console.log('URL            : ' + url);
			});
		} else {
			event.preventDefault();
		}
	});
});
