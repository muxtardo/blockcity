export default function __(string, arrayKeys = {}) {
	let stringTrans = I18n[string] || string;
	Object.entries(arrayKeys).forEach(([key, value]) => {
		console.log(key, value)
		stringTrans = stringTrans.replace(new RegExp(`\:${key}`, 'g'), value);
	});
	return stringTrans;
}
