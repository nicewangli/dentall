/* WooCommerce 11.0的邮箱输入没有字段过滤器，保留提交字段并显示第一版只读策略。 */
const accountEmail = document.getElementById('account_email');
const accountEmailNote = document.getElementById('dentall-account-email-note');

if (accountEmail && accountEmailNote) {
	accountEmail.readOnly = true;
	accountEmail.setAttribute('aria-describedby', accountEmailNote.id);
}
