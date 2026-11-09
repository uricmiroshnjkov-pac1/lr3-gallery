import $ from 'jquery';
import Popover from 'bootstrap/js/dist/popover';
$(() => {
 document.querySelectorAll('[data-bs-toggle="popover"]').forEach(element => new Popover(element, {trigger: 'focus'}));
 $('.delete-form').on('submit', function(event) { if (!window.confirm('Удалить картину из каталога?')) event.preventDefault(); });
 $('.painting-form').on('submit', function(event) {
  this.classList.add('was-validated');
  if (!this.checkValidity()) { event.preventDefault(); this.reportValidity(); }
 });
 $('.painting-form').on('invalid', 'input,textarea', function() { $(this).addClass('is-invalid'); });
 $('.painting-form').on('input', 'input,textarea', function() { $(this).removeClass('is-invalid'); });
 $('#image').on('change', function() {
  this.setCustomValidity(this.files[0]?.size > 8 * 1024 * 1024 ? 'Размер файла должен быть не более 8 МБ.' : '');
  this.reportValidity();
 });
});
