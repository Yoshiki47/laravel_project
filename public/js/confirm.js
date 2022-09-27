// form.blade.phpに記載
function checkSubmit() {
	if (window.confirm('登録してよろしいですか？')) {
		return true;
	} else {
		return false;
	}
}

// product.blade.phpに記載
function checkDelete() {
	if (window.confirm('削除してよろしいですか？')) {
		return true;
	} else {
		return false;
	}
}

// edit.blade.phpに記載
function checkUpdate() {
	if (window.confirm('更新してよろしいですか？')) {
		return true;
	} else {
		return false;
	}
}