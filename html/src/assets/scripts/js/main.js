let checkToEdit = document.querySelectorAll('.acc-edit');
let accNum = document.querySelectorAll('.accs-num');
let accPass = document.querySelectorAll('.accs-new-password');
let accRole = document.querySelectorAll('.accs-role');
let accBalance = document.querySelectorAll('.accs-balance');
let accDelete = document.querySelectorAll('.accs-delete');
let accId = document.querySelectorAll('.accs-id');

for(let i = 0; i < checkToEdit.length; i++){
	checkToEdit[i].addEventListener('change', () => {
		if(checkToEdit[i].checked){
			accNum[i].setAttribute('name', 'formAdmin_accsNum[]');
			accPass[i].setAttribute('name', 'formAdmin_accsPass[]');
			accRole[i].setAttribute('name', 'formAdmin_accsRole[]');
			accBalance[i].setAttribute('name', 'formAdmin_accsBalance[]');
			accDelete[i].setAttribute('name', 'formAdmin_accsDelete[]');
			accId[i].setAttribute('name', 'formAdmin_accsId[]');
		}else{
			accNum[i].removeAttribute('name');
			accPass[i].removeAttribute('name');
			accRole[i].removeAttribute('name');
			accBalance[i].removeAttribute('name');
			accDelete[i].removeAttribute('name');
			accId[i].removeAttribute('name');
		}
	})
}