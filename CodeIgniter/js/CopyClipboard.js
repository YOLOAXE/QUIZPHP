function CopyFonction(id) {
	var copyText = document.getElementById("KeyInfo" + id);
	copyText.select();
	copyToClipboard(copyText.value);
	alert("La clé -->  " + copyText.value + "  <-- a bien ete copier.");
}
const copyToClipboard = str => {
	const el = document.createElement('textarea');
	el.value = str;
	el.setAttribute('readonly', '');
	el.style.position = 'absolute';
	el.style.left = '-9999px';
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);
};
